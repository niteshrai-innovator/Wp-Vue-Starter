import axios from 'axios';

/* global wkwp_erp_addonAdminLocalizer */
export default axios.create({
    baseURL: wkwp_erp_addonAdminLocalizer.rest.root + wkwp_erp_addonAdminLocalizer.rest.version + '/wkwp-erp-addon/v1',
    headers: {
        'X-WP-Nonce': wkwp_erp_addonAdminLocalizer.rest.nonce
    }
});