<template>
    <div class="app-customers">
        <div class="people-header">
            <h2 class="add-new-people">
                <span>{{ pageTitle }}</span>
                <a href="" id="erp-customer-new" @click.prevent="showModal = true">{{ 'Add New' }} {{ buttonTitle }}</a>
            </h2>
            <!-- top search bar -->
        </div>

        <event-modal v-if="showModal" :people.sync="people" :title="buttonTitle" :type="peopleType" @close="showModal = false" />

        <list-table
            tableClass="wk-addon-wperp-table people-table table-striped table-dark "
            action-column="actions"
            :columns="columns"
            :rows="row_data"
            :bulk-actions="bulkActions"
            :total-items="paginationData.totalItems"
            :total-pages="paginationData.totalPages"
            :per-page="paginationData.perPage"
            :current-page="paginationData.currentPage"
            @pagination="goToPage"
            :actions="actions"
            @action:click="onActionClick"
            @bulk:click="onBulkAction">
            <template slot="title" slot-scope="data">
                <strong><a href="#">{{ data.row.title }}</a></strong>
            </template>
            <template slot="customer" slot-scope="data">
                <strong>
                    <router-link :to="{ name: singleUrl, params: { id: data.row.id, route: url}}">
                        {{data.row.customer}}
                    </router-link>
                </strong>
            </template>
        </list-table>
    </div>
</template>

<script>
import axios from 'axios';
import ListTable from '../list-table/ListTable.vue';
import EventModel from '../pages/inner-module/EventModel.vue';
export default {
    name: 'Events',
    components: {
        ListTable,
        EventModel,
    },
    data(){
        return {
            people:null,
            bulkActions:[
                {
                    key:"trash",
                    label:'Move To Tash',
                }
            ],
            columns: {
                customer: { label: 'Name', isColPrimary: true },
                company : { label: 'Company' },
                email   : { label: 'Email' },
                phone   : { label: 'Phone' },
                actions : { label: 'Actions' }
            },
            rows: [],
            paginationData: {
                totalItems : 0,
                totalPages : 0,
                perPage    : 10,
                currentPage: this.$route.params.page === undefined ? 1 : parseInt(this.$route.params.page)
            },
            actions : [
                { key: 'edit', label: 'Edit', iconClass: 'dashicons dashicons-edit' },
                { key: 'trash', label: 'Delete', iconClass: 'dashicons dashicons-trash' }
            ],
            showModal             : false,
            buttonTitle           : '',
            pageTitle             : 'Events',
            url                   : '',
            peopleType            : 'event',
            baseUrl : wkwp_erp_addonAdminLocalizer.apiUrl+ '/wkwp-erp-addon/v1/',
        }
    },
    created(){
        this.$on('modal-close', () => {
            this.showModal       = false;
            this.people          = null;
        });
        
        
        this.peopleType  = 'event';
        this.pageTitle   = 'Events';
        this.buttonTitle = 'event';
        this.url         = 'events';
        this.singleUrl   = 'EventDetails';
        

        this.fetchItems();
    },
    computed: {
        row_data() {
            const items = this.rows;
            items.map(item => {
                item.customer = item.first_name + ' ' + item.last_name;
            });
            return items;
        }
    },
    watch: {
        search(newVal, oldVal) {
            this.fetchItems();
        }
    },
    methods: {
        fetchItems() {            
            this.rows = [];
            var fetch_link = this.baseUrl+this.url;
            console.log(fetch_link);
            
            axios.get(fetch_link, {
                params: {
                    per_page: this.paginationData.perPage,
                    page: this.$route.params.page === undefined ? this.paginationData.currentPage : this.$route.params.page,
                    search: this.search
                }
            }).then((response) => {
                    this.rows = response.data;
                    this.paginationData.totalItems = parseInt(response.headers['x-wp-total']);
                    this.paginationData.totalPages = parseInt(response.headers['x-wp-totalpages']);
            }).catch((error) => {
                    throw error;
            });
        },

        onActionClick(action, row, index) {
            switch (action) {
            case 'trash':
                if (confirm('Are you sure to delete?')) {
                    axios.delete(this.baseUrl+this.url + '/delete/' + row.id).then(response => {
                        if ( response.status !== 204 ) {
                            this.showAlert('error', response.data.data[0].message);
                            // or loop through the erros and show a list
                            return;
                        }

                        this.$delete(this.rows, index);
                        this.showAlert('success', 'Deleted !');

                        this.fetchItems();
                    }).catch(error => {
                        throw error;
                    });
                }
                break;

            case 'edit':
                this.showModal = true;
                this.people = row;
                break;

            default :
                break;
            }
        },

        onBulkAction(action, items) {
            if (action === 'trash') {
                if (confirm('Are you sure to delete?')) {
                    this.$store.dispatch('spinner/setSpinner', true);
                    axios.delete(this.baseUrl+this.url + '/delete/' + items.join(',')).then(response => {
                        if ( response.status !== 204 ) {
                            this.$store.dispatch('spinner/setSpinner', false);
                            this.showAlert('error', response.data.data[0].message);

                            return;
                        }

                        const toggleCheckbox = document.getElementsByClassName('column-cb')[0].childNodes[0];

                        if (toggleCheckbox.checked) {
                            // simulate click event to remove checked state
                            toggleCheckbox.click();
                        }

                        this.fetchItems();
                        this.$store.dispatch('spinner/setSpinner', false);
                        this.showAlert('success', 'Deleted !');
                    }).catch(error => {
                        this.$store.dispatch('spinner/setSpinner', false);
                        throw error;
                    });
                }
            }
        },

        goToPage(page) {
            this.$store.dispatch('spinner/setSpinner', true);
            const queries = Object.assign({}, this.$route.query);
            this.paginationData.currentPage = page;

            this.$router.push({
                name: this.url === 'events' ? 'PaginateCustomers' : 'PaginateVendors',
                params: { page: page },
                query: queries
            });

            this.fetchItems();
        }
    }
    
}
</script>

<style>
.add-new-people {
    cursor: pointer;
    display: inline-block;
    margin-top: 10px;
}

.app-customers .people-header .add-new-people {
    align-items: center;
    display: flex;
    width: 65%;
    margin: 0;
    padding: 0;
}

.app-customers .people-header .add-new-people a {
    background: #1a9ed4;
    border-radius: 3px;
    color: #fff;
    font-size: 12px;
    height: 29px;
    line-height: 29px;
    margin-left: 13px;
    text-align: center;
    text-decoration: none;
    width: 150px;
}

</style>