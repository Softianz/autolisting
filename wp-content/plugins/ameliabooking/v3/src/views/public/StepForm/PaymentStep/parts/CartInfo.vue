<template>
  <div class="am-fs__payments-wrapper" :style="cssVars">

    <AmCollapse class="am-fs__payments-cart">
      <template
        v-for="(item, index) in useAppointmentsAmountInfo(store)"
        :key="index"
      >
        <AmCollapseItem
          v-if="(paymentGateway !== 'onSite' && item.depositAmount && store.getters['entities/getService'](item.serviceId).depositPayment !== 'disabled') || item.discountAmount"
          :ref="el => servicesRef[index] = el"
          :side="true"
          :delay="500"
          @collapse-open="collapseState[index] = false"
          @collapse-close="collapseState[index] = true"
        >
          <template #heading>
            <div class="am-fs__payments-cart-info">
              <span>
                {{ store.getters['entities/getService'](item.serviceId).name }}
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
                v-show="collapseState[index]"
                class="am-fs__payments-cart-sub"
              >
                <p>
                  {{ amLabels.total_price }}
                </p>
                <p class="am-amount">
                  {{ useFormattedPrice(item.totalAmount) }}
                </p>
              </div>
            </Transition>
          </template>
          <template #default>
            <div class="am-fs__payments-cart-open">
              <!-- Discount -->
              <div
                v-if="item.discountAmount"
                class="am-fs__payments-cart-open-text"
              >
                <span>
                  {{amLabels.summary_services_subtotal}}:
                </span>
                <span class="am-amount">
                  {{ useFormattedPrice(item.totalAmount + item.discountAmount) }}
                </span>
              </div>

              <div
                v-if="item.discountAmount"
                class="am-fs__payments-cart-open-text am-fs__payments-cart-open-text-discount"
              >
                <span>
                  {{amLabels.discount_amount_colon}}:
                </span>
                <span class="am-amount">
                  {{ useFormattedPrice(item.discountAmount) }}
                </span>
              </div>
              <!-- /Discount -->

              <div
                v-if="store.getters['entities/getService'](item.serviceId).depositPayment !== 'disabled' && item.depositAmount && paymentGateway !== 'onSite'"
                class="am-fs__payments-cart-open-text"
                :class="{'am-fs__payments-cart-open-text-border': item.discountAmount}"
              >
                <span>
                  {{amLabels.paying_now}}:
                </span>
                <span>
                  {{ useFormattedPrice(item.depositAmount) }}
                </span>
              </div>

              <div
                v-if="store.getters['entities/getService'](item.serviceId).depositPayment !== 'disabled' && item.depositAmount && paymentGateway !== 'onSite'"
                class="am-fs__payments-cart-open-text"
              >
                <span>
                  {{amLabels.paying_later}}:
                </span>
                <span>
                  {{ useFormattedPrice(item.totalAmount - item.depositAmount) }}
                </span>
              </div>

              <div
                class="am-fs__payments-cart-sub"
                :class="{'am-fs__payments-cart-sub-border': paymentGateway !== 'onSite' || item.discountAmount}"
              >
                <p>
                  {{ amLabels.total_price }}:
                </p>
                <p class="am-amount">
                  {{ useFormattedPrice(item.totalAmount) }}
                </p>
              </div>
            </div>
          </template>
        </AmCollapseItem>
        <div v-else class="am-fs__payments-cart-item">
          <div class="am-fs__payments-cart-info">
            <span>
              {{ store.getters['entities/getService'](item.serviceId).name }}
            </span>
          </div>
          <div class="am-fs__payments-cart-open">
            <div class="am-fs__payments-cart-sub">
              <p>
                {{ amLabels.total_price }}
              </p>
              <p class="am-amount">
                {{ useFormattedPrice(item.totalAmount + item.discountAmount) }}
              </p>
            </div>
          </div>
        </div>
      </template>
    </AmCollapse>

    <div class="am-fs__payments-app-info">
      <div class="am-fs__payments-app-info-subtotal">
        <span>{{amLabels.subtotal}}:</span>
        <span class="am-amount">{{ useFormattedPrice(cartAmount.totalAmount) }}</span>
      </div>

      <Coupon
        v-if="settings.payments.coupons"
        type="cart"
        :count="cart.length"
        :ids="useCart(store).filter(i => i.serviceId && (i.serviceId in i.services)).map(i => i.serviceId)"
        @coupon-applied="couponApplied"
      />

      <Transition name="am-fade">
        <div
          v-if="settings.payments.coupons"
          v-show="cartAmount.discountAmount > 0"
          class="am-fs__payments-app-info-discount"
          :class="{'am-fs__payments-app-info-discount-green': cartAmount.discountAmount > 0 }"
        >
          <span>{{amLabels.discount_amount_colon}}:</span>
          <span class="am-amount">
            {{ useFormattedPrice(cartAmount.discountAmount > cartAmount.totalAmount ? cartAmount.totalAmount : cartAmount.discountAmount) }}
          </span>
        </div>
      </Transition>

      <div
        class="am-fs__payments-app-info-total"
        :class="{'am-single-row': !settings.payments.coupons || cartAmount.discountAmount}"
      >
        <span>{{amLabels.total_amount_colon}}</span>
        <span class="am-amount">
          {{ useFormattedPrice(cartAmount.totalAmount) }}
        </span>
      </div>

      <div v-if="hasDeposit && paymentGateway !== 'onSite'">
        <div class="am-fs__payments-app-info-total">
          <span>{{ amLabels.paying_now }}:</span>
          <span class="am-amount">
            {{ useFormattedPrice(cartAmount.depositAmount) }}
          </span>
        </div>

        <div class="am-fs__payments-app-info-total">
          <span>{{ amLabels.paying_later }}:</span>
          <span class="am-amount">
            {{ useFormattedPrice(cartAmount.totalAmount - cartAmount.depositAmount) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// * Import from Vue
import {
  inject,
  ref,
  computed,
  watch
} from 'vue'

// * Import from Vuex
import { useStore } from 'vuex'

// * Composables
import {
  useFormattedPrice
} from '../../../../../assets/js/common/formatting.js'
import {
  useAppointmentsAmountInfo,
} from '../../../../../assets/js/common/appointments.js'
import {
  useCart
} from '../../../../../assets/js/public/cart'
import {
  useColorTransparency
} from '../../../../../assets/js/common/colorManipulation.js'

// * _components
import AmCollapseItem from "../../../../_components/collapse/AmCollapseItem.vue";
import AmCollapse from "../../../../_components/collapse/AmCollapse.vue";

// * Parts
import Coupon from '../../../Parts/Payment/Coupon.vue'

const store = useStore()

const amLabels = inject('amLabels')

const settings = inject('settings')

let cart = computed(() => useCart(store))

let cartAmount = computed(() => {
  let amountInfo = useAppointmentsAmountInfo(store)

  let amount = {
    totalAmount: 0,
    depositAmount: 0,
    discountAmount: 0,
  }

  amountInfo.forEach((item) => {
    amount.totalAmount += item.totalAmount
    amount.depositAmount += item.depositAmount
    amount.discountAmount += item.discountAmount
  })

  return amount
})

let paymentDeposit = computed(() => store.getters['booking/getPaymentDeposit'])

let hasDeposit = inject('hasDeposit')

let paymentGateway = computed(() => store.getters['booking/getPaymentGateway'])

let servicesRef = ref([])

let collapseState = ref({})

useAppointmentsAmountInfo(store).forEach((item, index) => {
  collapseState.value[index] = true
})

function collapseAllCards () {
  Object.keys(collapseState.value).forEach((key) => {
    collapseState.value[key] = true
  })

  servicesRef.value.forEach((item) => {
    if (item) {
      item.closingFromParent()
    }
  })
}

watch(paymentGateway, () => {
  collapseAllCards()
})

function onCollapseClose (el) {
  el.style.opacity = 0
  setTimeout(() => {
    el.style.opacity = 1
    el.style.height = 'var(--am-h-services-sub)'
  }, 200)
}

function onCollapseOpen (el) {
  el.style.opacity = 0
  el.style.setProperty('--am-h-services-sub', `${el.offsetHeight}px`)
  setTimeout(() => {
    el.style.height = '0px'
  }, 100)
}

const emits = defineEmits(['setOnSitePayment'])

function couponApplied () {
  emits('setOnSitePayment', cartAmount.value.totalAmount <= 0)
  collapseAllCards()
}

let amColors = inject('amColors')

let cssVars = computed(() => {
  return {
    '--am-c-pay-text': amColors.value.colorMainText,
    '--am-c-pay-text-op30': useColorTransparency(amColors.value.colorMainText, 0.3),
    '--am-c-pay-text-op60': useColorTransparency(amColors.value.colorMainText, 0.6),
    '--am-c-pay-text-op70': useColorTransparency(amColors.value.colorMainText, 0.7),
    '--am-c-pay-success': amColors.value.colorSuccess,
    '--am-c-pay-primary': amColors.value.colorPrimary,
  }
})
</script>


<script>
export default {
  name: 'CartInfo'
}
</script>

<style lang="scss">
.amelia-v2-booking {
  #amelia-container {
    .am-fs__payments {
      &-app-info {
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

      &-cart {
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
            padding: 12px;
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

          &-border {
            border-top: 1px dashed var(--am-c-pay-text-op30);
            padding: 12px 0 0;
            margin: 12px 0 4px;
          }

          p {
            font-size: 13px;
            font-weight: 500;
            line-height: 1.384615;
            color: var(--am-c-pay-text);
            margin: 0;
            padding: 0;
          }

          .am-amount {
            color: var(--am-c-pay-primary)
          }
        }

        &-open {
          width: 100%;

          &-text {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            line-height: 1.384615;
            color: var(--am-c-pay-text);

            &-discount {
              color: var(--am-c-pay-success);
            }

            &-border {
              border-top: 1px dashed var(--am-c-pay-text-op30);
              padding: 12px 0 0;
              margin: 12px 0 4px;
            }
          }
        }

        &-item {
          border: 1px solid var(--am-c-pay-text-op30);
          border-radius: 8px;
          padding: 12px;
          margin: 8px 0;
          box-sizing: border-box;
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
