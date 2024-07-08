<template>
  <div>
    <div class="am-congrats__main">
      <img
        v-if="props.booked.type !== 'event'"
        :src="props.baseUrls.wpAmeliaPluginURL+'/v3/src/assets/img/congratulations/congratulations.svg'"
      >

      <p v-if="props.booked.type !== 'event'" class="am-congrats__main-heading">
        {{ props.labels.congratulations }}
      </p>

      <p v-if="props.booked.type === 'event'" class="am-congrats__main-heading">
        {{ props.booked.data[0].title }}
      </p>

      <span v-if="itemIdLabel">
        {{ itemIdLabel }}
      </span>

      <slot name="positionBelowHeading"></slot>
    </div>

    <CongratsInfo>
      <template #info>
        <component
          :is="componentTypes[props.booked.type].template"
          v-bind="componentTypes[props.booked.type].props"
        >
          <template #payment>
            <PaymentCongratsInfo
              :coupon="props.coupon"
              :labels="props.labels"
              :booked="props.booked"
              :in-dialog="props.inDialog"
            ></PaymentCongratsInfo>
          </template>
        </component>
      </template>
      <template #customer>
        <CustomerCongratsInfo
          :labels="props.labels"
          :customer="props.customer"
          :in-dialog="props.inDialog"
        ></CustomerCongratsInfo>
      </template>
    </CongratsInfo>

    <slot name="positionBottom"></slot>
  </div>
</template>

<script setup>
// * Parts
import CongratsInfo from "../Parts/CongratsInfo.vue";
import EventCongratsInfo from "../Parts/EventCongratsInfo.vue";
import PaymentCongratsInfo from "../Parts/PaymentCongratsInfo.vue";

// * Import from Vue
import {
  computed,
  markRaw
} from "vue";
import CustomerCongratsInfo from "../Parts/CustomerCongratsInfo.vue";

let props = defineProps({
  baseUrls: {
    type: Object,
    required: true
  },
  labels: {
    type: Object,
    required: true
  },
  booked: {
    type: Object,
    required: true
  },
  customer: {
    type: Object,
    required: true
  },
  coupon: {
    type: Object,
    required: true
  },
  inDialog: {
    type: Boolean,
    default: false
  }
})

const componentTypes = {
  event: {
    template: markRaw(EventCongratsInfo),
    props: {
      booked: props.booked,
      labels: props.labels,
      'in-dialog': props.inDialog
    }
  }
}

let itemIdLabel = computed(() => {
  if (props.booked && props.booked.data.length) {
    if (props.booked.type === 'appointment') {
      return `${props.labels.appointment_id} #${props.booked.data[0].appointmentId}`
    }

    if (props.booked.type === 'event') {
      return `${props.labels.event_id} #${props.booked.data[0].eventId}`
    }
  }

  return ''
})
</script>

<script>
export default {
  name: "CongratulationsPage"
}
</script>

<style lang="scss">
.amelia-v2-booking #amelia-container {
  .am-congrats {
    &__main {
      display: flex;
      margin-top: 22px;
      flex-direction: column;
      align-items: center;
      font-family: var(--am-font-family);
      margin-bottom: 16px;

      & > * {
        $count: 5;
        @for $i from 0 through $count {
          &:nth-child(#{$i + 1}) {
            animation: 600ms cubic-bezier(.45,1,.4,1.2) #{$i*100}ms am-animation-slide-up;
            animation-fill-mode: both;
          }
        }
      }

      & img {
        width:54px;
        margin-bottom: 8px;
      }

      &-heading {
        margin-bottom: 2px;
        font-weight: 500;
        font-size: 18px;
        line-height: 28px;
        color: var(--am-c-atc-heading-text);
      }

      &-atc {
        margin-top: 16px
      }

      & span {
        color: var(--am-c-atc-heading-text-op40);
        font-weight: 400;
        font-size: 14px;
        line-height: 1.42857;
      }
    }
  }
}
</style>