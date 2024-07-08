function useEventLocation (event, locations) {
    let locationAddress = ''

    if (event.locationId && locations.find(l => l.id === event.locationId)) {
        let location = locations.filter(location => location.id === event.locationId)[0]
        locationAddress = location.address

        if (!locationAddress) {
            locationAddress = location.name
        }
    }

    if (event.customLocation) {
        locationAddress = event.customLocation
    }

    return locationAddress
}

function useMinTicketPrice (event) {
  let priceArray = []

  event.customTickets.forEach(ticket => {
    if (ticket.enabled) {
      let tPrice = ticket.price
      if (ticket.dateRangePrice) {
        tPrice = ticket.dateRangePrice
      }
      priceArray.push(tPrice)
    }
  })

  return Math.min(...priceArray)
}

function useCheckIfEventNotFree (event) {
  let priceArray = []

  if (event.customPricing) {
    event.customTickets.forEach(ticket => {
      if (ticket.enabled) {
        let tPrice = ticket.price
        if (ticket.dateRangePrice) {
          tPrice = ticket.dateRangePrice
        }

        if (tPrice > 0) {
          priceArray.push(tPrice)
        }
      }
    })

    return priceArray.length > 0
  }

  return event.price > 0
}

// function getEventAvailability (evt, labels) {
//   if (evt.status === 'approved' || evt.status === 'pending') {
//     if (evt.full) {
//       return {
//         label: labels.full,
//         class: 'full'
//       }
//     }
//     if (evt.upcoming) {
//       return {
//         label: labels.upcoming,
//         class: 'upcoming'
//       }
//     }
//     return !evt.bookable ? {
//       label: 'Closed', //amLabels.closed,
//       class: 'closed'
//     } : {
//       label: labels.open,
//       class: 'open'
//     }
//   } else {
//     return {
//       label: labels.canceled,
//       class: 'canceled'
//     }
//   }
// }

function showEventCapacity (status) {
    switch (status) {
        case 'open':
        case 'upcoming':
            return true
        case 'canceled':
        case 'closed':
        case 'full':
            return false
        default:
            return true
    }
}

function useEventStatus (evt) {
  if (evt.status === 'approved' || evt.status === 'pending') {
    if (evt.full) return 'full'
    if (evt.upcoming) return 'upcoming'
    return !evt.bookable ? 'closed' : 'open'
  } else {
    return 'canceled'
  }
}

export {
  // getEventAvailability,
  useEventLocation,
  useMinTicketPrice,
  showEventCapacity,
  useEventStatus,
  useCheckIfEventNotFree
}
