<template>
  <div
    class="am-pei"
    :style="cssVars"
  >
    <div class="am-pei__inner">

      <AmCollapse
        v-if="tickets.length"
        class="am-pei__segment"
      >
        <AmCollapseItem
          :side="true"
          @collapse-open="segmentCollapseState = false"
          @collapse-close="segmentCollapseState = true"
        >
          <template #heading>
            <div class="am-pei__segment-info">
              <span>
                {{ amLabels.summary_event }}
              </span>
            </div>
          </template>
          <template #icon-below>
            <Transition
              :duration="{enter: 500, leave: 500}"
              @enter="onCollapseClose"
              @leave="onCollapseOpen"
            >
              <div
                v-show="segmentCollapseState"
                class="am-pei__segment-sub"
              >
                <p>
                  {{ `${selectedEvent.name} x ${eventPersonsNumber} ${personsLabel(eventPersonsNumber)}` }}
                </p>
                <p class="am-amount">
                  {{ useFormattedPrice(eventSubtotalPrice) }}
                </p>
              </div>
            </Transition>
          </template>
          <template #default>
            <div class="am-pei__segment-open">
              <template
                v-for="ticket in tickets"
                :key="ticket.id"
              >
                <div
                  v-if="ticket.persons"
                  class="am-pei__segment-open__text"
                >
                  <span>
                    {{ ticket.name }} {{ticketDisplayCalculation(ticket)}}
                  </span>
                  <span class="am-amount">
                    {{ useFormattedPrice(selectedEvent.aggregatedPrice ? ticket.price * ticket.persons : ticket.price) }}
                  </span>
                </div>
              </template>

              <div class="am-pei__segment-sub">
                <p>
                  {{ selectedEvent.name }}
                </p>
                <p class="am-amount">
                  {{ useFormattedPrice(eventSubtotalPrice) }}
                </p>
              </div>
            </div>
          </template>
        </AmCollapseItem>
      </AmCollapse>

      <div
        v-else
        class="am-pei__segment-wrapper"
      >
        <div class="am-pei__segment">
          <div class="am-pei__segment-info">
          <span>
            {{ amLabels.summary_event }}
          </span>
          </div>
          <div class="am-pei__segment-open">
            <div class="am-pei__segment-sub">
              <p>
                {{ `${selectedEvent.name} (${selectedEvent.aggregatedPrice ? useFormattedPrice(selectedEvent.price) : (persons + ' ' + personsLabel(persons))})`}}
                <span v-if="selectedEvent.aggregatedPrice">
                  {{ `x ${persons} ${personsLabel(persons)}`}}
                </span>
              </p>
              <p class="am-amount"> {{ useFormattedPrice(eventSubtotalPrice) }} </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="am-pei__info">
      <div class="am-pei__info-subtotal">
        <span>{{amLabels.subtotal}}:</span>
        <span class="am-amount">
          {{ useFormattedPrice(eventSubtotalPrice) }}
        </span>
      </div>
      <Coupon
        v-if="amSettings.payments.coupons"
        :bookings-count="1"
        :entity-id="selectedEvent.id"
        :bookable-type="bookableType"
        @coupon-applied="couponApplied"
      />

      <Transition name="am-fade">
        <div
          v-if="amSettings.payments.coupons"
          v-show="discount > 0"
          class="am-pei__info-discount"
          :class="{'am-pei__info-discount-green': discount > 0 }"
        >
          <span>
            {{ `${amLabels.discount_amount_colon}:` }}
          </span>
          <span class="am-amount">
            {{ useFormattedPrice(discount) }}
          </span>
        </div>
      </Transition>

      <div
        class="am-pei__info-total"
        :class="{'am-single-row': !discount}"
      >
        <span>{{ amLabels.total_amount_colon }}</span>
        <span class="am-amount">
          {{ useFormattedPrice(totalAmount) }}
        </span>
      </div>

      <template v-if="hasDeposit && paymentGateway !== 'onSite'">
        <div class="am-pei__info-total">
          <span>
            {{ amLabels.paying_now }}:
          </span>
          <span class="am-amount">
            {{ paymentDeposit ? useFormattedPrice(totalAmount) : useFormattedPrice(depositAmount) }}
          </span>
        </div>

        <div class="am-pei__info-total">
          <span>
            {{ amLabels.paying_later }}:
          </span>
          <span class="am-amount">
            {{ !paymentDeposit ? useFormattedPrice(totalAmount - depositAmount) : useFormattedPrice(0) }}
          </span>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
// * _components
import Coupon from '../../../Parts/Payment/Coupon/Coupon.vue'
import AmCollapse from '../../../../_components/collapse/AmCollapse'
import AmCollapseItem from '../../../../_components/collapse/AmCollapseItem'

// * Import for Vue
import {
  inject,
  provide,
  ref,
  computed,
  onMounted
} from 'vue'

// * Import from Vuex
import { useStore } from 'vuex'

// * Composables
import { useFormattedPrice } from '../../../../../assets/js/common/formatting.js'
import { useEventDepositAmount } from '../../../../../assets/js/public/payment.js'
import { useColorTransparency } from '../../../../../assets/js/common/colorManipulation.js'

const store = useStore()

// * Root Settings
const amSettings = inject('settings')

// * Computed labels
let amLabels = inject('amLabels')

let bookableType = computed(() => store.getters['bookableType/getType'])

let selectedEvent = computed(() => store.getters['eventEntities/getEvent'](store.getters['eventBooking/getSelectedEventId']))

let tickets = computed(() => store.getters['tickets/getTicketsData'])

let persons = computed(() => store.getters['persons/getPersons'])

function personsLabel (num) {
  return num > 1 ? amLabels.summary_persons : amLabels.summary_person
}

function ticketDisplayCalculation (ticket) {
  if (selectedEvent.value.aggregatedPrice) {
    return `(${useFormattedPrice(ticket.price)}) x ${ticket.persons} ${personsLabel(ticket.persons)}`
  }

  return `(${ticket.persons} ${personsLabel(ticket.persons)})`
}

let eventSubtotalPrice = computed(() => {
  let price = 0
  if (selectedEvent.value.customPricing) {
    tickets.value.forEach(t => {
      if (t.persons) {
        price += selectedEvent.value.aggregatedPrice ? t.price * t.persons : t.price
      }
    })
    return price
  }

  return selectedEvent.value.aggregatedPrice ? selectedEvent.value.price * persons.value : selectedEvent.value.price
})

let eventPersonsNumber = computed(() => {
  let ePersons = 0

  if (selectedEvent.value.customPricing) {
    tickets.value.forEach(t => {
      ePersons += t.persons
    })

    return ePersons
  }

  return persons.value
})

let paymentDeposit = computed(() => store.getters['payment/getPaymentDeposit'])

let hasDeposit = inject('hasDeposit')

let discount = computed(() => {
  let coupon = store.getters['coupon/getCoupon']
  let discountAmount = ref(0)

  if (coupon && coupon.limit) {
    let calculation = eventSubtotalPrice.value / 100 * coupon.discount + coupon.deduction
    discountAmount.value = calculation > eventSubtotalPrice.value ? eventSubtotalPrice.value : calculation
  }

  return discountAmount.value
})

provide('discount', discount)

let totalAmount = computed(() => {
  return eventSubtotalPrice.value - discount.value
})

let depositAmount = computed(() => useEventDepositAmount(
    store,
    selectedEvent.value,
    totalAmount.value
  )
)

let paymentGateway = computed(() => store.getters['payment/getPaymentGateway'])

const emits = defineEmits(['setOnSitePayment'])

function couponApplied () {
  emits('setOnSitePayment', totalAmount.value <= 0)
}

onMounted(() => {

})

// * Collapse visualisation
let segmentCollapseState = ref(true)

function onCollapseClose (el) {
  el.style.opacity = 0
  setTimeout(() => {
    el.style.opacity = 1
    el.style.height = 'var(--am-h__part-sub)'
  }, 200)
}

function onCollapseOpen (el) {
  el.style.opacity = 0
  el.style.setProperty('--am-h__part-sub', `${el.offsetHeight}px`)
  setTimeout(() => {
    el.style.height = '0px'
  }, 100)
}

// * Fonts
let amFonts = inject('amFonts')

// * Colors
let amColors = inject('amColors')

// * Css Variables
let cssVars = computed(() => {
  return {
    '--am-font-family': amFonts.value.fontFamily,
    '--am-c-pay-text': amColors.value.colorMainText,
    '--am-c-pay-text-op70': useColorTransparency(amColors.value.colorMainText, 0.7),
    '--am-c-pay-text-op60': useColorTransparency(amColors.value.colorMainText, 0.6),
    '--am-c-pay-text-op30': useColorTransparency(amColors.value.colorMainText, 0.3),
    '--am-c-pay-text-op20': useColorTransparency(amColors.value.colorMainText, 0.2),
    '--am-c-pay-border': useColorTransparency(amColors.value.colorInpBorder),
    '--am-c-pay-success': amColors.value.colorSuccess,
    '--am-c-pay-primary': amColors.value.colorPrimary,
  }
})
</script>

<script>
export default {
  name: 'EventPaymentInfo'
}
</script>

<style lang="scss">

// pei - payment even info
.amelia-v2-booking {
  #amelia-container {
    .am-pei {

      & > * {
        word-break: break-word;
      }

      &__info {
        margin: 0;

        &-subtotal {
          display: flex;
          justify-content: space-between;
          border-bottom: 1px dashed var(--am-c-pay-text-op30);
          padding: 0 0 16px;
          margin: 16px 0 0;

          span {
            font-size: 14px;
            font-weight: 500;
            line-height: 1.42857;
            color: var(--am-c-pay-text);
          }
        }

        &-discount {
          display: flex;
          justify-content: space-between;
          font-size: 13px;
          font-weight: 400;
          line-height: 1.3846;
          color: var(--am-c-pay-text);
          padding: 16px 0 0;

          &-green {
            color: var(--am-c-pay-success);
          }
        }

        &-total {
          display: flex;
          justify-content: space-between;
          font-size: 15px;
          font-weight: 500;
          line-height: 1.33333;
          color: var(--am-c-pay-text);
          padding: 8px 0 0;

          &.am-single-row {
            padding-top: 16px;
          }

          & > span:nth-child(2) {
            color: var(--am-c-pay-primary);
          }
        }
      }

      &-service {
        border: 1px solid var(--am-c-pay-text-op30);
        border-radius: 8px;
        padding: 12px;
        box-sizing: border-box;
      }

      &__segment {
        &-wrapper {
          border: 1px solid var(--am-c-pay-border);
          padding: 12px;
          border-radius: 8px;
        }

        .am-collapse-item__heading {
          flex-wrap: wrap;
          width: 100%;
          padding: 12px;
          transition-delay: .5s;

          &-side {
            transition-delay: 0s;
          }

          .am-collapse-item__trigger {
            padding: 0
          }
        }

        .am-collapse-item__content {
          & > * {
            padding: 8px 12px;
          }
        }

        &-info {
          span {
            font-size: 12px;
            line-height: 1.33333;
            font-weight: 500;
            color: var(--am-c-pay-text-op60);
          }
        }

        &-sub {
          width: 100%;
          display: flex;
          justify-content: space-between;
          transition: all ease-in-out .3s;

          p {
            font-size: 13px;
            font-weight: 500;
            line-height: 1.384615;
            color: var(--am-c-pay-text);
            margin: 0;
            padding: 0;
          }
        }

        &-open {
          width: 100%;

          &__total {
            border-top: 1px dashed var(--am-c-pay-text-op30);
            padding: 12px 0 0;
            margin: 12px 0 4px;

            & > span {
              color: var(--am-c-pay-text-op70)
            }

            &:last-child {
              font-weight: 500;
            }
          }

          &__text {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            line-height: 1.384615;
            color: var(--am-c-pay-text);
            border-bottom: 1px dashed var(--am-c-pay-text-op20);

            span {
              display: inline-flex;
              font-size: 13px;
              font-weight: 400;
              line-height: 1.53846;
              padding: 4px 0;
              color: var(--am-c-pay-text);
            }

            .am-amount {
              font-weight: 500;
            }

            &:first-child {
              span {
                padding: 0 0 4px;

                &.am-amount {
                  padding-left: 4px;
                }
              }
            }

            &:nth-last-child(2) {
              border-bottom: none;
            }
          }

          .am-pei__segment-sub {
            padding: 4px 0 0;
            margin-top: 4px;
            border-top: 1px solid var(--am-c-pay-text-op20);
          }
        }
      }

      .am-amount {
        white-space: nowrap;
      }

      .am-fade-enter-active,
      .am-fade-leave-active {
        transition: opacity 0.5s ease;
      }

      .am-fade-enter-from,
      .am-fade-leave-to {
        opacity: 0;
      }
    }
  }
}
</style>
