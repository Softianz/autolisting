import moment from 'moment';
import {useUrlQueryParams} from "../../assets/js/common/helper";

function getDateString (date) {
  return moment(date).format('YYYY-MM-DD')
}

export default {
  namespaced: true,

  state: () => ({
    params: {
      id: null,
      tag: null,
      search: null,
      recurring: null,
      dates: moment().toDate(),
      locationId: null,
      providers: null,
    },
    shortcodeParams: {
      ids: null,
      tags: null
    }
  }),

  getters: {
    getTag (state) {
      return state.params.tag
    },

    getSearch (state) {
      return state.params.search
    },

    getLocationIdParam (state) {
      return state.params.locationId
    },

    getDates (state) {
      return state.params.dates
    },

    getEventParams (state) {
      return {
        dates: [getDateString(state.params.dates)],
        id: state.params.id ? state.params.id : state.shortcodeParams.ids,
        search: state.params.search,
        locationId: state.params.locationId,
        tag: state.params.tag ? state.params.tag : state.shortcodeParams.tags,
        recurring: state.params.recurring,
        providers: state.params.providers
      }
    },

    getShortcodeParams (state) {
      return {
        ids: state.shortcodeParams.ids,
        tags: state.shortcodeParams.tags
      }
    },

    getAllData (state) {
      return {
        dates: state.params.dates,
        id: state.params.id,
        search: state.params.search,
        locationId: state.params.locationId,
        tag: state.params.tag,
        recurring: state.params.recurring,
        providers: state.params.providers
      }
    }
  },

  mutations: {
    setTag (state, payload) {
      state.params.tag = payload ? payload : null
    },

    setLocationIdParam (state, payload) {
      state.params.locationId = payload ? payload : null
    },

    setId (state, payload) {
      state.params.id = payload
    },

    // * Params for Events
    // ! needs to change name of function
    setParams (state, payload) {
      let urlParameters = useUrlQueryParams(window.location.href)

      if (payload.eventId) {
        state.shortcodeParams.ids = payload.eventId.split(',')
      }
      if (urlParameters && urlParameters.ameliaEventId) {
        state.shortcodeParams.ids = urlParameters.ameliaEventId.split(',')
      }

      if (payload.eventTag) {
        state.shortcodeParams.tags = payload.eventTag.split("{").map(e => e.replace('},', '').replace('}', '')).filter(e => e !== '')
      }
      if (urlParameters && urlParameters.ameliaEventTag) {
        state.shortcodeParams.tags = urlParameters.ameliaEventTag.split(',')
      }

      if (payload.eventRecurring) {
        state.params.recurring = payload.eventRecurring
      }
    },

    setSearch (state, payload) {
      state.params.search = payload ? payload : null
    },

    setDates (state, payload) {
      state.params.dates = payload
    },

    setAllData (state, payload) {
      state.params = {
        dates: payload.dates,
        id: payload.id ? parseInt(payload.id) : null,
        search: payload.search,
        locationId: payload.locationId ? parseInt(payload.locationId) : null,
        tag: payload.tag,
        recurring: payload.recurring,
        providers: payload.providers
      }
    }
  },

  actions: {}
}