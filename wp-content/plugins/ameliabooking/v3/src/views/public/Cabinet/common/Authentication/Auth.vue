<template>
  <component :is="authPagesObj[pageKey]"></component>
</template>

<script setup>
// * import from Vue
import {
  ref,
  reactive,
  computed,
  markRaw,
  inject,
  provide
} from 'vue'

// * Form Component Collection
import SignIn from './parts/SignIn.vue'
import SendAccessLink from './parts/SendAccessLink.vue'
import SendAccessLinkProcess from './parts/SendAccessLinkSuccess.vue'
import SetPass from './parts/SetPass.vue'
import ChangePassSuccess from './parts/SetPassSuccess.vue'

// * import composable
import {
  defaultCustomizeSettings
} from '../../../../../assets/js/common/defaultCustomize.js'

// * Root Settings
const amSettings = inject('settings')

// * Customized form data
let amCustomize = inject('amCustomize')

// * labels
const labels = inject('labels')

// * local language short code
const localLanguage = inject('localLanguage')

// * if local lang is in settings lang
let langDetection = computed(() => amSettings.general.usedLanguages.includes(localLanguage.value))

// * Computed labels
let amLabels = computed(() => {
  let computedLabels = reactive({...labels})

  // if (amSettings.customizedData && amSettings.customizedData.cbf && amSettings.customizedData.cbf.categoriesList.translations) {
  //   let customizedLabels = amSettings.customizedData.cbf.categoriesList.translations
  //   Object.keys(customizedLabels).forEach(labelKey => {
  //     if (customizedLabels[labelKey][localLanguage.value] && langDetection.value) {
  //       computedLabels[labelKey] = customizedLabels[labelKey][localLanguage.value]
  //     } else if (customizedLabels[labelKey].default) {
  //       computedLabels[labelKey] = customizedLabels[labelKey].default
  //     }
  //   })
  // }
  return computedLabels
})

provide('amLabels', amLabels)

// * Auth pages
let authPagesObj = ref({
  signIn: markRaw(SignIn),
  sendAccessLink: markRaw(SendAccessLink),
  sendAccessLinkProcess: markRaw(SendAccessLinkProcess),
  setPassword: markRaw(SetPass),
  changeSuccess: markRaw(ChangePassSuccess),
})

let originKey = inject('originKey')

// * Page key
let pageKey = ref('signIn')
provide('pageKey', pageKey)

// * Fonts
const amFonts = ref(amSettings.customizedData ? amSettings.customizedData.fonts : defaultCustomizeSettings.fonts)
provide('amFonts', amFonts)

// * Colors block
let amColors = computed(() => {
  return amSettings.customizedData &&
    originKey.value in amSettings.customizedData
    ? amSettings.customizedData[originKey.value].colors
    : defaultCustomizeSettings[originKey.value].colors
})

provide('amColors', amColors)
</script>

<script>
export default {
  name: 'AuthWrapper'
}
</script>
