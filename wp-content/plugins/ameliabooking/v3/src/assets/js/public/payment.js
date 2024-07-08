function useEventDepositAmount (store, event, totalAmount) {
  let depositAmount = 0

  if (event.depositPayment !== 'disabled') {
    switch (event.depositPayment) {
      case ('fixed'):
        if (event.customPricing) {
          depositAmount = ((event.depositPerPerson && event.aggregatedPrice) ? store.getters['tickets/getTicketsSum'] : 1) * event.deposit
        } else {
          depositAmount = ((event.depositPerPerson && event.aggregatedPrice) ? store.getters['persons/getPersons'] : 1) * event.deposit
        }

        break

      case 'percentage':
        depositAmount = totalAmount / 100 * event.deposit

        break
    }
  }

  return totalAmount >= depositAmount ? depositAmount : totalAmount
}

export {
  useEventDepositAmount
}