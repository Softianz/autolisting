function usePackageBookingPrice (pack) {
  return pack.price - pack.price / 100 * pack.discount
}

export {
  usePackageBookingPrice,
}
