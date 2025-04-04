<template>
    <div :class="{ 'table-loading': loading }">

        <div v-if="loading" class="table-loader-wrap">
            <div class="table-loader-center">
                <div class="table-loader">{{ 'Loading' }}</div>
            </div>
        </div>

        <div class="tablenav top">

            <div class="alignleft actions">
                <slot name="filters"/>
            </div>

            <div class="tablenav-pages">
                <span v-if="showItemNumbers" class="displaying-num">{{ itemsTotal }} {{ 'items' }}</span>

                <span v-if="hasPagination" class="pagination-links">
                    <span v-if="disableFirst" class="tablenav-pages-navspan"
                          aria-hidden="true">&laquo;</span>
                    <a v-else href="#"
                       class="first-page" @click.prevent="goToPage(1);"><span aria-hidden="true">&laquo;</span></a>

                    <span v-if="disablePrev" class="tablenav-pages-navspan"
                          aria-hidden="true">&lsaquo;</span>
                    <a v-else href="#"
                       class="prev-page" @click.prevent="goToPage(currentPage - 1);"><span
                        aria-hidden="true">&lsaquo;</span></a>

                    <span class="paging-input">
                        <span class="tablenav-paging-text">
                            <input :value="currentPage" class="current-page"
                                type="text" name="paged" aria-describedby="table-paging" size="1"
                                @keyup.enter="goToCustomPage"> of
                            <span class="total-pages">{{ totalPages }}</span>
                        </span>
                    </span>

                    <span v-if="disableNext" class="tablenav-pages-navspan"
                          aria-hidden="true">&rsaquo;</span>
                    <a v-else href="#"
                       class="next-page" @click.prevent="goToPage(currentPage + 1);"><span
                        aria-hidden="true">&rsaquo;</span></a>

                    <span v-if="disableLast" class="tablenav-pages-navspan"
                          aria-hidden="true">&raquo;</span>
                    <a v-else href="#"
                       class="last-page" @click.prevent="goToPage(totalPages)"><span
                        aria-hidden="true">&raquo;</span></a>
                </span>
            </div>
        </div>

        <table :class="tableClass">
            <thead>
            <bulk-actions-tpl v-if="checkedItems.length"
                              :select-all="selectAll"
                              :bulk-actions="bulkActions"
                              :show-cb="showCb"
                              :columns-count="columnsCount"/>

            <tr v-else>
                <td v-if="showCb" class="manage-column column-cb check-column col--check">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input v-model="selectAll" type="checkbox" class="form-check-input">
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </td>
                <th v-for="(value, key) in columns" :key="key" :class="[
                        'column',
                        key,
                        (value.isColPrimary) ? 'column-primary' : '',
                        { 'sortable': isSortable(value) },
                        { 'sorted': isSorted(key) },
                        { 'asc': isSorted(key) && sortOrder === 'asc' },
                        { 'desc': isSorted(key) && sortOrder === 'desc' }
                        ]">
                    <template v-if="!isSortable(value)">
                        {{ value.label }}
                    </template>
                    <a v-else href="#"
                       @click.prevent="handleSortBy(key)">
                        <span>{{ value.label }}</span>
                        <span class="sorting-indicator"/>
                    </a>
                </th>
            </tr>
            </thead>
            <tfoot>
            <slot name="tfoot">
                <bulk-actions-tpl v-if="checkedItems.length"
                                  :select-all="selectAll"
                                  :bulk-actions="bulkActions"
                                  :show-cb="showCb"
                                  :columns-count="columnsCount"/>

                <tr v-else>
                    <td v-if="showCb" class="manage-column column-cb check-column">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input v-model="selectAll" type="checkbox" class="form-check-input">
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </td>
                    <th v-for="(value, key) in columns" :key="key"
                        :class="['column', key, (value.isColPrimary) ? 'column-primary' : '', ]">{{ value.label }}
                    </th>
                </tr>
            </slot>
            </tfoot>
            <tbody>
            <template v-if="rows.length">
                <tr v-for="(row, i) in rows" :key="row[index]" :class="collapsRow(row)">
                    <th v-if="showCb" scope="row" class="col--check check-column">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input :value="row[index]" v-model="checkedItems" class="form-check-input"
                                       type="checkbox" name="item[]">
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </th>
                    <td v-for="(value, key) in columns" :key="key" :data-colname="ucFirst(key)"
                        :class="['column', key, (value.isColPrimary) ? 'column-primary' : '', { 'selected': checkedItems.includes(row[index]) }]">
                        <slot :name="key" :row="row">
                            <template v-if="'actions' !== key">
                                {{ row[key] ? row[key] : '-' }}
                            </template>
                        </slot>
                        

                        <div v-if="actionColumn === key" class="row-actions">
                            <slot :row="row" name="row-actions">
                                <dropdown placement="left-start">
                                    <template slot="button">
                                        <a class="dropdown-trigger"><i class="dashicons dashicons-ellipsis"></i></a>
                                    </template>
                                    <template slot="dropdown">
                                        <ul slot="action-items" role="menu" class="horizontal-scroll-wrapper">
                                            <slot :row="row" name="action-list">
                                                <li v-for="action in actions" :key="action.key" :class="action.key">
                                                    <a href="#" @click.prevent="actionClicked(action.key, row, i)"><i
                                                        :class="action.iconClass"></i>{{ action.label }}</a>
                                                </li>
                                            </slot>
                                        </ul>
                                    </template>
                                </dropdown>
                            </slot>
                        </div>

                    </td>
                </tr>
            </template>
            <tr v-else>
                <td :colspan="colspan" class="not-found">{{ notFound }}</td>
            </tr>
            </tbody>
        </table>
        <div class="tablenav bottom">

            <div class="tablenav-pages">
                <span v-if="showItemNumbers" class="displaying-num">{{ itemsTotal }} {{ 'items' }}</span>

                <span v-if="hasPagination" class="pagination-links">
            <span v-if="disableFirst" class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
            <a v-else href="#"
               class="first-page" @click.prevent="goToPage(1);"><span aria-hidden="true">&laquo;</span></a>

            <span v-if="disablePrev" class="tablenav-pages-navspan"
                  aria-hidden="true">&lsaquo;</span>
            <a v-else href="#"
               class="prev-page" @click.prevent="goToPage(currentPage - 1);"><span
                aria-hidden="true">&lsaquo;</span></a>

            <span class="paging-input">
                <span class="tablenav-paging-text">
                    {{ currentPage }} of
                    <span class="total-pages">{{ totalPages }}</span>
                </span>
            </span>

            <span v-if="disableNext" class="tablenav-pages-navspan"
                  aria-hidden="true">&rsaquo;</span>
            <a v-else href="#"
               class="next-page" @click.prevent="goToPage(currentPage + 1);"><span
                aria-hidden="true">&rsaquo;</span></a>

            <span v-if="disableLast" class="tablenav-pages-navspan"
                  aria-hidden="true">&raquo;</span>
            <a v-else href="#"
               class="last-page" @click.prevent="goToPage(totalPages)"><span aria-hidden="true">&raquo;</span></a>
        </span>
            </div>
        </div>
    </div>
</template>

<script>
import Dropdown from '../action-link/Dropdown.vue';
export default {

    name: 'ListTable',

    components: {
        Dropdown
    },

    props: {
        columns: {
            type: Object,
            required: true,
            default: () => {
            }
        },
        rows: {
            type: Array, // String, Number, Boolean, Function, Object, Array
            required: true,
            default: () => []
        },
        index: {
            type: String,
            default: 'id'
        },
        showCb: {
            type: Boolean,
            default: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        actionColumn: {
            type: String,
            default: ''
        },
        actions: {
            type: Array,
            required: false,
            default: () => []
        },
        bulkActions: {
            type: Array,
            required: false,
            default: () => []
        },
        tableClass: {
            type: String,
            default: 'wp-list-table widefat fixed striped'
        },
        notFound: {
            type: String,
            default: 'No items found.'
        },
        totalItems: {
            type: Number,
            default: 0
        },
        totalPages: {
            type: Number,
            default: 1
        },
        perPage: {
            type: Number,
            default: 20
        },
        currentPage: {
            type: Number,
            default: 1
        },
        sortBy: {
            type: String,
            default: null
        },
        sortOrder: {
            type: String,
            default: 'asc'
        },
        showItemNumbers: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            bulkLocal: '-1',
            checkedItems: [],
            isRowExpanded: []
        };
    },

    computed: {

        hasActions() {
            return this.actions.length > 0;
        },

        itemsTotal() {
            return this.totalItems || this.rows.length;
        },

        hasPagination() {
            return this.itemsTotal > this.perPage;
        },

        disableFirst() {
            if (this.currentPage === 1 || this.currentPage === 2) {
                return true;
            }

            return false;
        },

        disablePrev() {
            if (this.currentPage === 1) {
                return true;
            }

            return false;
        },

        disableNext() {
            if (this.currentPage === this.totalPages) {
                return true;
            }

            return false;
        },

        disableLast() {
            if (this.currentPage === this.totalPages || this.currentPage === (this.totalPages - 1)) {
                return true;
            }

            return false;
        },

        columnsCount() {
            return Object.keys(this.columns).length;
        },

        colspan() {
            let columns = Object.keys(this.columns).length;

            if (this.showCb) {
                columns += 1;
            }

            return columns;
        },

        selectAll: {

            get() {
                if (!this.rows.length) {
                    return false;
                }

                return this.rows ? this.checkedItems.length === this.rows.length : false;
            },

            set(value) {
                const selected = [];
                const self = this;

                if (value) {
                    this.rows.forEach((item) => {
                        if (item[self.index] !== undefined) {
                            selected.push(item[self.index]);
                        } else {
                            selected.push(item.id);
                        }
                    });
                }

                this.checkedItems = selected;
            }
        }

    },

    created() {
        this.$on('bulk-checkbox', e => {
            if (!e) {
                this.checkedItems = [];
            }
        });

        this.$on('bulk-action-click', key => {
            this.bulkLocal = key;
            this.handleBulkAction();
        });
    },

    methods: {

        collapsRow(obj) {
            if (this.isRowExpanded.findIndex(x => x === obj.id) === -1) {
                return '';
            } else {
                return 'is-row-expanded';
            }
        },

        toggleRow(obj) {
            const i = this.isRowExpanded.findIndex(x => x === obj.id);
            if (i === -1) {
                this.isRowExpanded.push(obj.id);
            } else {
                this.isRowExpanded.splice(i, 1);
            }
        },

        // Capitalize First Letter
        ucFirst(string) {
            return string.replace(/^./, string[0].toUpperCase());
        },

        hideActionSeparator(action) {
            return action === this.actions[this.actions.length - 1].key;
        },

        actionClicked(action, row, index) {
            this.$emit('action:click', action, row, index);
        },

        goToPage(page) {
            this.$emit('pagination', page);
        },

        goToCustomPage(event) {
            const page = parseInt(event.target.value, 10);

            if (!isNaN(page) && (page > 0 && page <= this.totalPages)) {
                this.$emit('pagination', page);
            }
        },

        handleBulkAction() {
            if (this.bulkLocal === '-1') {
                return;
            }

            this.$emit('bulk:click', this.bulkLocal, this.checkedItems);
        },

        isSortable(column) {
            if (Object.prototype.hasOwnProperty.call(column, 'sortable') && column.sortable === true) {
                return true;
            }

            return false;
        },

        isSorted(column) {
            return column === this.sortBy;
        },

        handleSortBy(column) {
            const order = this.sortOrder === 'asc' ? 'desc' : 'asc';

            this.$emit('sort', column, order);
        }
    }
};
</script>

<style>
.wk-addon-wperp-table  {
    position: relative;
    width: 100%;
    margin-bottom: 1rem;
    background-color: #fff;
    border-radius: 3px;
}

.wk-addon-wperp-table thead tr {
    box-shadow: 0 5px 12px 0 rgba(0,100,235,.06);
    transform: translateY(-0.1px);
}

.wk-addon-wperp-table td, .wk-addon-wperp-table th {
    color: #525252;
    padding: 18px 10px;
    vertical-align: middle;
    text-align: initial;
    border-top: 2px solid #f6f6f6;
}

.wk-addon-wperp-table thead td, .wk-addon-wperp-table thead th {
    vertical-align: bottom;
    border: none;
    font-size: 1.077em;
}

.wk-addon-wperp-table thead tr th {
    font-weight: 300;
}
</style>
