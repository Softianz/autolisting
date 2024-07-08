import {useTimeInSeconds} from "./date";
import {useCart, useCartItem} from "../public/cart";
import {useSortedDateStrings} from "./helper";
import {settings} from "../../../plugins/settings";
import {checkLimitPerEmployee, sortForEmployeeSelection} from "./employee";

function useEmployeeService (store, serviceId, employeeId) {
  return employeeId
    ? store.getters['entities/getEmployeeService'](employeeId, serviceId)
    : store.getters['entities/getService'](serviceId)
}

function useAppointmentServiceAmount (employeeService, persons, duration) {
  let price = employeeService.customPricing.enabled && (duration in employeeService.customPricing.durations)
    ? employeeService.customPricing.durations[duration].price : employeeService.price

  return price * (employeeService.aggregatedPrice ? persons : 1)
}

function useCartItemTotalAmount (store, item) {
  let value = 0

  item.services[item.serviceId].list.forEach((appointment) => {
    value += useAppointmentsTotalAmount(
      store,
      useEmployeeService(store, item.serviceId, appointment.providerId),
      [appointment]
    )
  })

  return value
}

function useCartTotalAmount (store) {
  let value = 0

  useCart(store).forEach((item) => {
    value += useCartItemTotalAmount(store, item)
  })

  return parseFloat(parseFloat(value).toFixed(2))
}

function useAppointmentTotalAmount (service, appointment) {
  let value = useAppointmentServiceAmount(service, appointment.persons, appointment.duration)

  appointment.extras.forEach((selectedExtra) => {
    value += useAppointmentExtraAmount(service, selectedExtra, appointment.persons)
  })

  return value
}

function useAppointmentDiscountAmount (service, appointment, coupon) {
  let amount = useAppointmentServiceAmount(service, appointment.persons, appointment.duration)

  appointment.extras.forEach((selectedExtra) => {
    amount += useAppointmentExtraAmount(service, selectedExtra, appointment.persons)
  })

  return parseFloat(parseFloat((amount / 100 * coupon.discount).toFixed(2)) + coupon.deduction)
}

function useAppointmentsDiscountAmount (store) {
  let value = 0

  let coupon = store.getters['booking/getCoupon']

  if (coupon && coupon.limit) {
    let couponLimit = coupon.limit

    let servicesIds = coupon.servicesIds

    useCart(store).filter(i => servicesIds.indexOf(i.serviceId) !== -1).forEach((item) => {
      item.services[item.serviceId].list.forEach((appointment) => {
        if (couponLimit) {
          value += couponLimit ? useAppointmentDiscountAmount(
            useEmployeeService(store, item.serviceId, appointment.providerId),
            appointment,
            coupon
          ) : 0

          couponLimit--
        }
      })
    })
  }

  return parseFloat(value)
}

function useAppointmentAmountData (store, item, coupon, couponLimit) {
  let depositAmount = 0

  let totalAmount = 0

  let discountAmount = 0

  let useFullAmount = store.getters['booking/getPaymentDeposit']

  let service = store.getters['entities/getService'](
    item.serviceId
  )

  let applyCoupon = couponLimit > 0 && coupon.servicesIds.indexOf(service.id) !== -1

  let instantPaymentCount = 1

  if (service.recurringPayment) {
    instantPaymentCount = service.recurringPayment > item.services[item.serviceId].list.length
      ? item.services[item.serviceId].list.length : service.recurringPayment
  }

  let appliedCoupon = false

  item.services[item.serviceId].list.slice(0, instantPaymentCount).forEach((appointment) => {
    let employeeService = useEmployeeService(store, item.serviceId, appointment.providerId)

    let appointmentTotalAmount = useAppointmentTotalAmount(employeeService, appointment)

    if (applyCoupon && couponLimit > 0) {
      appliedCoupon = true

      discountAmount = useAppointmentDiscountAmount(employeeService, appointment, coupon)

      appointmentTotalAmount = appointmentTotalAmount >= discountAmount ? appointmentTotalAmount - discountAmount : 0

      couponLimit--
    }

    if (service.depositPayment !== 'disabled' && (useFullAmount ? !service.fullPayment : true)) {
      switch (service.depositPayment) {
        case ('fixed'):
          depositAmount += (service.depositPerPerson && service.aggregatedPrice
            ? appointment.persons : 1) * service.deposit

          break

        case 'percentage':
          depositAmount += parseFloat(parseFloat(appointmentTotalAmount / 100 * service.deposit).toFixed(2))

          break
      }
    } else {
      depositAmount += appointmentTotalAmount
    }

    totalAmount += parseFloat(appointmentTotalAmount)
  })

  item.services[item.serviceId].list.slice(instantPaymentCount).forEach((appointment) => {
    totalAmount += useAppointmentTotalAmount(
      useEmployeeService(store, item.serviceId, appointment.providerId),
      appointment
    )
  })

  return {
    serviceId: service.id,
    totalAmount: totalAmount,
    depositAmount: depositAmount,
    discountAmount: discountAmount,
    appliedCoupon: appliedCoupon,
    couponLimit: couponLimit
  }
}

function useAppointmentsAmountInfo (store) {
  let amountsInfo = []

  let coupon = store.getters['booking/getCoupon']

  let couponLimit = coupon && coupon.limit ? coupon.limit : 0

  useCart(store).forEach((item) => {
    let amountData = useAppointmentAmountData(store, item, coupon, couponLimit)

    couponLimit = amountData.couponLimit

    delete (amountData.couponLimit)

    amountsInfo.push(amountData)
  })

  return amountsInfo
}

function useAppointmentsDepositAmount (store) {
  let coupon = store.getters['booking/getCoupon']

  let couponLimit = coupon && coupon.limit ? coupon.limit : 0

  let amount = {
    totalAmount: 0,
    depositAmount: 0,
    discountAmount: 0,
  }

  useCart(store).forEach((item) => {
    let itemAmount = useAppointmentAmountData(store, item, coupon, couponLimit)

    amount.totalAmount += itemAmount.totalAmount

    amount.depositAmount += itemAmount.depositAmount

    amount.discountAmount += itemAmount.discountAmount
  })

  return amount.totalAmount > amount.depositAmount ? amount.depositAmount : 0
}

function useCapacity (employeeServices) {
  let options = {
    availability: false,
    min: 0,
    max: 0
  }

  let serviceMinCapacity = 0

  if (employeeServices.length && employeeServices.length > 1) {
    employeeServices.forEach(service => {
      serviceMinCapacity = service.minCapacity

      options.availability = service.bringingAnyone && service.maxCapacity > 1 && (service.maxExtraPeople === null || service.maxExtraPeople > 0)

      if (service.maxCapacity > options.max || options.max === 0) {
        options.max = service.maxExtraPeople !== null ? (service.maxExtraPeople + 1) : service.maxCapacity
      }

      if (options.min < service.minCapacity) {
        options.min = settings.appointments.allowBookingIfNotMin ? 1 : service.minCapacity
      }
    })

  } else if (employeeServices.length && employeeServices.length === 1) {
    let service = employeeServices[0]

    serviceMinCapacity = service.minCapacity

    options.availability = service.bringingAnyone && service.maxCapacity > 1 && (service.maxExtraPeople === null || service.maxExtraPeople > 0)
    options.min = settings.appointments.allowBookingIfNotMin ? 1 : service.minCapacity
    options.max = service.maxExtraPeople !== null && service.maxExtraPeople < service.maxCapacity ? (service.maxExtraPeople + 1) : service.maxCapacity
  }

  if (settings.appointments.openedBookingAfterMin) {
    options.min = serviceMinCapacity
  }

  options.max = options.max > 1 ? (options.max - 1) : options.max
  options.min = options.min > 0 ? (options.min - 1) : options.min

  return options
}

function usePaymentError (store, message) {
  store.commit('booking/setError', message)
}

function usePrepaidPrice (store) {
  let type = store.getters['bookableType/getType'] ? store.getters['bookableType/getType'] : store.getters['booking/getBookableType']

  let entity = null

  switch (type) {
    case 'appointment':
      let appointmentPrice = 0

      useCart(store).forEach((item) => {
        if ((item.serviceId && (item.serviceId in item.services)) || item.packageId) {
          entity = store.getters['entities/getService'](
            item.serviceId
          )

          appointmentPrice += useAppointmentsTotalAmount(
            store,
            entity,
            useAppointmentsPayments(
              store,
              item.serviceId,
              item.services[item.serviceId].list
            ).prepaid
          )
        }
      })

      return appointmentPrice
    case 'package':
      let price = 0

      if (useCart(store)[0].packageId) {
        entity = store.getters['entities/getPackage'](
          useCart(store)[0].packageId
        )

        price = entity.price
      }

      return price
    case 'event':
      entity = store.getters['eventEntities/getEvent'](store.getters['eventBooking/getSelectedEventId'])

      let eventPrice = 0

      if (entity.customPricing) {
        let tickets = store.getters['tickets/getTicketsData']

        tickets.forEach(t => {
          eventPrice += t.price * t.persons
        })

        return eventPrice
      }

      let persons = store.getters['persons/getPersons']

      return entity.price * persons
  }
}

function useDuration (serviceDuration, extras) {
  let duration = serviceDuration

  extras.forEach((extra) => {
    duration += (extra.duration * extra.quantity)
  })

  return duration
}

function useCalendarEvents (slots) {
  let calendarSlotsValues = []

  useSortedDateStrings(Object.keys(slots)).forEach((date) => {
    calendarSlotsValues.push({
      title  : 'e',
      start  : date,
      display: 'background',
      extendedProps: {
        slotsTotal: 100,
        slotsAvailable: 1,
        slots: slots[date]
      }
    })
  })

  return calendarSlotsValues
}

function useServices (store) {
  let type = store.getters['booking/getBookableType']

  let services = null

  switch (type) {
    case ('appointment'): {
      services = store.getters['entities/getServices']

      break
    }

    case ('package'): {
      let packages = store.getters['entities/getPackages']

      let packagesServices = {}

      packages.forEach((pack) => {
        pack.bookable.forEach((book) => {
          packagesServices[book.service.id] = book.service
        })
      })

      services = Object.values(packagesServices)

      break
    }
  }

  return services
}

function useBusySlots (store) {
  let cartItem = useCartItem(store)

  let activeAppointment = cartItem.services[cartItem.serviceId].list[cartItem.index]

  let busySlots = store.getters['booking/getMultipleAppointmentsOccupied']

  return busySlots[activeAppointment.date] ? Object.keys(busySlots[activeAppointment.date]) : []
}

function useAvailableSlots (store) {
  let cart = store.getters['booking/getAllMultipleAppointments']

  let cartIndex = store.getters['booking/getCartItemIndex']

  let activeAppointment = cart[cartIndex].services[cart[cartIndex].serviceId].list[cart[cartIndex].index]

  let services = useServices(store)

  if (activeAppointment.date) {
    let selectedSlots = []

    cart.forEach((cartItem, selectedCartIndex) => {
      for (let serviceId in cartItem.services) {
        let service = services.find(i => i.id === parseInt(serviceId))

        cartItem.services[serviceId].list.forEach((i, selectedListIndex) => {
          let isCurrentAppointment = selectedCartIndex === parseInt(cartIndex) && selectedListIndex === parseInt(cart[cartIndex].index)



          if (i.date && i.date === activeAppointment.date && i.time && !isCurrentAppointment) {
            selectedSlots.push({
              time: i.time,
              duration: service.duration + i.extras.filter(e => e.quantity && e.duration).map(e => e.duration).reduce((fullDuration, duration) => fullDuration + duration, 0),
              timeAfter: service.timeAfter,
              timeBefore: service.timeBefore
            })
          }
        })
      }
    })

    let service = services.find(i => i.id === cart[cartIndex].serviceId)

    let defaultSlots = Object.keys(cart[cartIndex].services[cart[cartIndex].serviceId].slots[activeAppointment.date])

    let availableSlots = {}

    for (let i = 0; i < defaultSlots.length; i++) {
      let defaultSlotSeconds = useTimeInSeconds(defaultSlots[i])

      let isFreeSlot = true

      for (let j = 0; j < selectedSlots.length; j++) {
        let slotInSeconds = useTimeInSeconds(selectedSlots[j].time)

        if (defaultSlotSeconds > (slotInSeconds - service.duration - service.timeAfter) &&
          defaultSlotSeconds < (slotInSeconds + selectedSlots[j].duration + selectedSlots[j].timeBefore + service.timeAfter)
        ) {
          isFreeSlot = false

          break
        }
      }

      if (isFreeSlot || defaultSlots[i] === activeAppointment.time) {
        availableSlots[defaultSlots[i]] = useTimeInSeconds(defaultSlots[i])
      }
    }

    return useSortedDateStrings(Object.keys(availableSlots))
  }

  return 'slots' in activeAppointment ? activeAppointment.slots : []
}

function useFillAppointments (store) {
  let cartItem = useCartItem(store)

  if (!cartItem.packageId &&
    Object.keys(cartItem.services).length === 1 &&
    cartItem.services[cartItem.serviceId].list.length === 1
  ) {
    let booking = cartItem.services[cartItem.serviceId].list[0]

    if (!booking.providerId && booking.date && booking.time) {
      let employeesIds = cartItem.services[cartItem.serviceId].slots[booking.date][booking.time].map(
        i => i[0]
      ).filter(
        (v, i, a) => a.indexOf(v) === i
      )

      if (settings.roles.limitPerEmployee.enabled) {
        let chosenEmployees = store.getters['booking/getAllMultipleAppointments'].map(a => Object.values(a.services)[0].list[0])
        let appCount = store.getters['booking/getMultipleAppointmentsAppCount'](cartItem.serviceId);
        let result = checkLimitPerEmployee(employeesIds, 0, [], booking, appCount, chosenEmployees, cartItem.serviceId)
        if (result.bookingFailed !== null) {
          return {booking: result.bookingFailed, serviceId: parseInt(cartItem.serviceId)}
        }
        employeesIds = result.employeeIds
      }

      if (settings.appointments.employeeSelection === 'random') {
        booking.providerId = employeesIds[Math.floor(Math.random() * (employeesIds.length) + 1) - 1]
      } else {
        employeesIds = sortForEmployeeSelection(store, employeesIds, cartItem.serviceId)
        booking.providerId = employeesIds[0]
      }
    }

    if (!booking.locationId && booking.date && booking.time) {
      let locationsIds = cartItem.services[cartItem.serviceId].slots[booking.date][booking.time].filter(
        i => i[0] === booking.providerId
      ).map(i => i[1])

      booking.locationId = locationsIds.length ? getPreferredEntityId(
        cartItem.services[cartItem.serviceId].slots[booking.date],
        booking.date in cartItem.services[cartItem.serviceId].occupied
          ? cartItem.services[cartItem.serviceId].occupied[booking.date] : {},
        booking.time,
        booking.providerId,
        locationsIds,
        1
      ) : null
    }

    let slots = store.getters['booking/getMultipleAppointmentsSlots']

    let existingApp = booking.date in slots && booking.time in slots[booking.date] &&  slots[booking.date][booking.time].length > 0 ?
      slots[booking.date][booking.time].find(s => s[0] === booking.providerId) : null

    store.commit('booking/setMultipleAppointmentsExistingApp', existingApp && existingApp[2] && existingApp[2] > 0)
  } else {
    let chosenEmployees = []
    for (let serviceId of Object.keys(cartItem.services)) {
      if (cartItem.services[serviceId].list.length &&
          cartItem.services[serviceId].list.filter(i => i.date && i.time).length
      ) {
        let bookingFailed = setPreferredEntitiesData(cartItem.services[serviceId], store, serviceId, chosenEmployees)
        if (bookingFailed !== null) {
          return {booking: bookingFailed, serviceId: parseInt(serviceId)}
        }
        chosenEmployees = chosenEmployees.concat(cartItem.services[serviceId].list.map((l) => { return { date: l.date, providerId: l.providerId, serviceId: serviceId, existingApp: l.existingApp} }))
      }
    }
  }

  let activeItemServices = cartItem.services

  Object.keys(activeItemServices).forEach((serviceId) => {
    if (activeItemServices[serviceId].list.filter(i => i.date && i.time).length) {
      activeItemServices[serviceId].list.forEach((booking) => {
        if (booking.date && booking.time) {
          setProviderServicePrice(store, booking.providerId, serviceId)
        }
      })
    }
  })

  return null
}

function setProviderServicePrice (store, employeeId, serviceId) {
  let employee = store.getters['entities/getUnfilteredEmployee'](employeeId)

  let service = employee.serviceList.find(i => i.id === parseInt(serviceId))

  let duration = store.getters['booking/getDuration']

  if (store.getters['booking/getDuration'] in service.customPricing.durations) {
    service.duration = duration

    service.price = service.customPricing.durations[duration].price
  }
}

function getAppointmentServiceAmount (store, serviceId, appointment) {
  let employeeService = store.getters['entities/getEmployeeService'](appointment.providerId, serviceId)

  return employeeService.price * (employeeService.aggregatedPrice ? store.getters['booking/getBookingPersons'] : 1)
}

function useAppointmentsAmount (store, service, appointments) {
  let amount = 0

  appointments.forEach((appointment) => {
    amount += useAppointmentServiceAmount(
      useEmployeeService(store, service.id, appointment.providerId),
      appointment.persons,
      appointment.duration
    )
  })

  return amount
}

function useAppointmentExtraAmount (service, selectedExtra, persons) {
  let extra = service.extras.find(item => item.id === parseInt(selectedExtra.extraId))

  if (extra) {
    let extraAggregatedPrice = extra.aggregatedPrice === null ? service.aggregatedPrice : extra.aggregatedPrice

    return extra.price * selectedExtra.quantity * (extraAggregatedPrice ? persons : 1)
  }

  return 0
}

function useAppointmentExtrasAmount (service, appointments) {
  let amount = 0

  appointments.forEach((appointment) => {
    if (appointment.extras) {
      appointment.extras.forEach((selectedExtra) => {
        amount += useAppointmentExtraAmount(service, selectedExtra, appointment.persons)
      })
    }
  })

  return amount
}

function useAppointmentsTotalAmount (store, service, appointments) {
  return useAppointmentsAmount(store, service, appointments) + useAppointmentExtrasAmount(service, appointments)
}

function useDiscountAmount (store, coupon, appointments) {
  let service = store.getters['entities/getService'](
    store.getters['booking/getServiceId']
  )

  let discountAmount = 0

  appointments.forEach((appointment) => {
    let employeeService = useEmployeeService(store, service.id, appointment.providerId)

    let amount = useAppointmentServiceAmount(
      useEmployeeService(store, employeeService.id, appointment.providerId),
      appointment.persons,
      appointment.duration
    )

    appointment.extras.forEach((selectedExtra) => {
      amount += useAppointmentExtraAmount(employeeService, selectedExtra, appointment.persons)
    })

    discountAmount += parseFloat((amount / 100 * coupon.discount).toFixed(2)) + coupon.deduction
  })

  return discountAmount
}

function useAppointmentsPayments (store, serviceId, appointments) {
  let service = store.getters['entities/getService'](
    serviceId
  )

  let prepaidCount = 1

  if (service.recurringPayment) {
    prepaidCount = service.recurringPayment > appointments.length
      ? appointments.length : service.recurringPayment
  }

  return {
    prepaid: appointments.slice(0, prepaidCount),
    postpaid: appointments.slice(prepaidCount),
  }
}

function useServicePrices (store, serviceId, appointments) {
  let data = {}

  appointments.map(i => i.providerId).forEach((providerId) => {
    let service = store.getters['entities/getEmployeeService'](
      providerId,
      serviceId
    )

    if (!(service.price in data)) {
      data[service.price] = 0
    }

    data[service.price]++
  })

  return data
}

function useAppointmentsLabels (store, serviceId, appointments) {
  let data = useServicePrices(store, serviceId, appointments)

  let persons = store.getters['booking/getBookingPersons']

  let labels = []

  for (let price in data) {
    let count = data[price]

    labels.push(
      count + ' ' + (count > 1 ? 'recurrences' : 'recurrence')
      + ' x ' + persons + ' ' + (persons > 1 ? 'persons' : 'person')
      + ' x ' + useFormattedPrice(price)
    )
  }

  return labels
}

function useExtrasLabels (store, service, appointments) {
  let labels = []

  let selectedExtras = store.getters['booking/getSelectedExtras']

  let persons = store.getters['booking/getBookingPersons']

  selectedExtras.forEach((selectedExtra) => {
    let count = appointments.length

    let extra = service.extras.find(i => i.id === parseInt(selectedExtra.extraId))

    if (extra) {
      labels.push(
          {
            name: extra.name,
            value: count + ' ' + (count > 1 ? 'appointments' : 'appointment')
                + ' x ' + selectedExtra.quantity + ' x '
                + (persons + ' ' + (persons > 1 ? 'persons' : 'person'))
                + ' x ' + useFormattedPrice(extra.price)
          }
      )
    }

  })

  return labels
}

function setPreferredEntitiesData (bookings, store, serviceId, chosenEmployees) {
  let employeesIds = getAllEntitiesIds(bookings, 0)

  let locationsIds = getAllEntitiesIds(bookings, 1)

  let isSingleEmployee = employeesIds.length === 1

  let isSingleLocation = locationsIds.length === 1

  let appCount = store.getters['booking/getMultipleAppointmentsAppCount'](serviceId);

  for (let bookingIndex = 0; bookingIndex < bookings.list.length; bookingIndex++) {
    let booking = bookings.list[bookingIndex]
    if (booking.date && booking.time) {
      employeesIds = sortForEmployeeSelection(store, employeesIds, serviceId)

      if (settings.roles.limitPerEmployee.enabled) {
        let result = checkLimitPerEmployee(employeesIds, bookingIndex, bookings.list, booking, appCount, chosenEmployees, serviceId)
        if (result.bookingFailed !== null) {
          return result.bookingFailed
        }
        employeesIds = result.employeeIds
      }

      if (!locationsIds.length && isSingleEmployee) {
        booking.providerId = employeesIds[0]

        booking.locationId = null
      } else if (!locationsIds.length && !isSingleEmployee) {
        booking.locationId = null

        for (let i = 0; i < employeesIds.length; i++) {
          for (let j = 0; j < bookings.slots[booking.date][booking.time].length; j++) {
            if (bookings.slots[booking.date][booking.time][j][0] === employeesIds[i]) {
              booking.providerId = employeesIds[i]

              break
            }
          }
        }
      } else if (isSingleLocation && isSingleEmployee) {
        booking.providerId = employeesIds[0]

        booking.locationId = locationsIds[0]
      } else if (!isSingleLocation && isSingleEmployee) {
        booking.providerId = employeesIds[0]

        booking.locationId = getPreferredEntityId(
          bookings.slots[booking.date],
          booking.date in bookings.occupied ? bookings.occupied[booking.date] : {},
          booking.time,
          booking.providerId,
          locationsIds,
          1
        )
      } else if (isSingleLocation && !isSingleEmployee) {
        booking.locationId = locationsIds[0]

        booking.providerId = getPreferredEntityId(
          bookings.slots[booking.date],
          booking.date in bookings.occupied ? bookings.occupied[booking.date] : {},
          booking.time,
          booking.locationId,
          employeesIds,
          0
        )
      } else {
        let setEntities = false
        outsideLoop: for (let j = 0; j < employeesIds.length; j++) {
          for (let i = 0; i < locationsIds.length; i++) {
            let isPreferred = isPreferredLocationAndEmployee(
              bookings.slots[booking.date],
              booking.date in bookings.occupied ? bookings.occupied[booking.date] : {},
              booking.time,
              locationsIds[i],
              employeesIds[j]
            )

            if (isPreferred) {
              booking.providerId = employeesIds[j]

              booking.locationId = locationsIds[i]

              setEntities = true

              break outsideLoop
            }
          }
        }

        if (!setEntities) {
          outsideLoop2: for (let j = 0; j < employeesIds.length; j++) {
            for (let i = 0; i < locationsIds.length; i++) {
              for (let k = 0; k < bookings.slots[booking.date][booking.time].length; k++) {
                if (bookings.slots[booking.date][booking.time][k][0] === employeesIds[j] &&
                    bookings.slots[booking.date][booking.time][k][1] === locationsIds[i]
                ) {
                  booking.providerId = employeesIds[j]

                  booking.locationId = locationsIds[i]

                  break outsideLoop2
                }
              }
            }
          }
        }
      }

      let slots = store.getters['booking/getMultipleAppointmentsSlots']
      let existingApp = booking.date in slots && booking.time in slots[booking.date] &&  slots[booking.date][booking.time].length > 0 ?
          slots[booking.date][booking.time].find(s => s[0] === booking.providerId) : null
      bookings.list[bookingIndex].existingApp = existingApp && existingApp[2] && existingApp[2] > 0

      store.commit('booking/setLastBookedProviderId', {providerId: booking.providerId, fromBackend: false})

    }
  }

  return null
}

function getAllEntitiesIds (bookings, index) {
  let ids = {}

  for (let i = 0; i < bookings.list.length; i++) {
    if (bookings.list[i].date && bookings.list[i].time) {
      bookings.slots[bookings.list[i].date][bookings.list[i].time].forEach((slotData) => {
        if (slotData[index]) {
          if (!(slotData[index] in ids)) {
            ids[slotData[index]] = 0
          }

          ids[slotData[index]]++
        }
      })
    }
  }

  let sortedEntitiesIds = []

  Object.keys(ids).forEach((id) => {
    sortedEntitiesIds.push({id: parseInt(id), quantity: ids[id]})
  })

  sortedEntitiesIds.sort((a, b) => b.quantity - a.quantity)

  return sortedEntitiesIds.map(entity => entity.id)
}

function getPreferredEntityId (availableSlots, occupiedSlots, timeString, selectedId, allIds, targetIndex) {
  let searchIndex = targetIndex ? 0 : 1

  let appointmentsStarts = {}

  Object.keys(occupiedSlots).forEach((time) => {
    occupiedSlots[time].forEach((slotData) => {
      if (slotData[searchIndex] === selectedId) {
        appointmentsStarts[useTimeInSeconds(time)] = slotData[targetIndex]
      }
    })
  })

  Object.keys(availableSlots).forEach((time) => {
    availableSlots[time].forEach((slotData) => {
      if (slotData.length >= 3 && slotData[searchIndex] === selectedId) {
        appointmentsStarts[useTimeInSeconds(time)] = slotData[targetIndex]
      }
    })
  })

  let availableIds = []

  availableSlots[timeString].forEach((slotData) => {
    if (slotData[searchIndex] === selectedId) {
      availableIds.push(slotData[targetIndex])
    }
  })

  if (Object.keys(appointmentsStarts).length) {
    let timeInSeconds = useTimeInSeconds(timeString)

    let closestSlot = Object.keys(appointmentsStarts).reduce((a, b) => {
      return Math.abs(b - timeInSeconds) < Math.abs(a - timeInSeconds) ? b : a
    })

    if (availableIds.indexOf(appointmentsStarts[closestSlot]) !== -1) {
      return appointmentsStarts[closestSlot]
    }
  }

  for (let i = 0; i < allIds.length; i++) {
    for (let j = 0; j < availableSlots[timeString].length; j++) {
      if (availableSlots[timeString][j][searchIndex] === selectedId &&
        allIds[i] === availableSlots[timeString][j][targetIndex]
      ) {
        return availableSlots[timeString][j][targetIndex]
      }
    }
  }

  return null
}

function isPreferredLocationAndEmployee (slotsData, occupiedData, timeString, locationId, employeeId) {
  let isEmployeeLocation = false

  slotsData[timeString].forEach((slotData) => {
    if (slotData[0] === employeeId && slotData[1] === locationId) {
      isEmployeeLocation = true
    }
  })

  // inspect if employee is available on proposed location
  if (!isEmployeeLocation) {
    return false
  }

  let appointmentStarts = {
    onLocation: {},
    offLocation: {}
  }

  Object.keys(occupiedData).forEach((time) => {
    occupiedData[time].forEach((slotData) => {
      if (slotData[0] === employeeId && slotData[1] === locationId) {
        appointmentStarts.onLocation[useTimeInSeconds(time)] = slotData[1]
      } else if (slotData[0] === employeeId) {
        appointmentStarts.offLocation[useTimeInSeconds(time)] = slotData[1]
      }
    })
  })

  Object.keys(slotsData).forEach((time) => {
    slotsData[time].forEach((slotData) => {
      if (slotData.length >= 3 && slotData[0] === employeeId && slotData[1] === locationId) {
        appointmentStarts.onLocation[useTimeInSeconds(time)] = slotData[1]
      } else if (slotData.length >= 3 && slotData[0] === employeeId) {
        appointmentStarts.offLocation[useTimeInSeconds(time)] = slotData[1]
      }
    })
  })

  // inspect if employee has appointments only on proposed location, or has no appointments in that day
  if (
    (!Object.keys(appointmentStarts.onLocation).length && !Object.keys(appointmentStarts.offLocation).length) ||
    (Object.keys(appointmentStarts.onLocation).length && !Object.keys(appointmentStarts.offLocation).length)
  ) {
    return true
  }

  let timeInSeconds = useTimeInSeconds(timeString)

  appointmentStarts = Object.assign(appointmentStarts.onLocation, appointmentStarts.offLocation)

  let closestTime = Object.keys(appointmentStarts).reduce((a, b) => {
    return Math.abs(b - timeInSeconds) < Math.abs(a - timeInSeconds) ? b : a
  })

  return locationId === appointmentStarts[closestTime]
}

export {
  useCapacity,
  useAvailableSlots,
  useBusySlots,
  useFillAppointments,
  useCalendarEvents,
  useAppointmentsPayments,
  useCartTotalAmount,
  useCartItemTotalAmount,
  useAppointmentsAmountInfo,
  useAppointmentTotalAmount,
  useAppointmentServiceAmount,
  useAppointmentExtrasAmount,
  useAppointmentExtraAmount,
  useAppointmentsAmount,
  useDiscountAmount,
  useAppointmentDiscountAmount,
  useAppointmentsDiscountAmount,
  useAppointmentsDepositAmount,
  useAppointmentsTotalAmount,
  useDuration,
  usePrepaidPrice,
  usePaymentError,
  useServices,
  useEmployeeService,
}
