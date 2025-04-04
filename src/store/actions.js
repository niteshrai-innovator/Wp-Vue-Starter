import Axios from 'axios'

export const actions = {
    SAVE_SETTINGS: ( { commit }, payload ) => {
        commit( 'SAVING' )
        let url = wkwp_erp_addonAdminLocalizer.apiUrl + '/wkwp-erp-addon/v1/settings'
        Axios.post( url, {
            firstname: payload.firstname,
            lastname : payload.lastname,
            email    : payload.email,
        } )
        .then( ( response ) => {
            commit( 'SAVED' )
        } )
        .catch( ( error ) => {
            console.log( error )
        } )
    },

    FETCH_SETTINGS: ( { commit }, payload ) => {
        let url = wkwp_erp_addonAdminLocalizer.apiUrl + '/wkwp-erp-addon/v1/settings'
        Axios.get( url )
        .then( ( response ) => {
            payload = response.data
            commit( 'UPDATE_SETTINGS', payload )
        } )
        .catch( ( error ) => {
            console.log( error )
        } )
    }
}