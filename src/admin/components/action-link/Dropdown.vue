<template>
    <div class="dropdown wperp-has-dropdown" @click.prevent="toggleDropdown">
        <slot name="button">
            <button class="btn btn-secondary dropdown-toggle"
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                {{ 'Dropdown' }}
            </button>
        </slot>
        <div ref="menu" :class="['dropdown-popper dropdown-menu', dropdownClasses, {'show': visible}]" @click.stop="">
            <div class="popper__arrow" x-arrow/>
            <slot name="dropdown"/>
        </div>
    </div>
</template>

<script>
import { createPopper } from '@popperjs/core';

export default {

    name: 'Dropdown',

    props: {
        dropdownClasses: {
            type: String,
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        },
        placement: {
            type: String,
            default: 'bottom'
        }
    },

    data() {
        return {
            visible: false
        };
    },

    watch: {
        visible(newValue, oldValue) {
            if (newValue !== oldValue) {
                if (newValue) {
                    this.showMenu();
                } else {
                    this.hideMenu();
                }
            }
        }
    },

    created() {
        // Create non-reactive property
        this._popper = null;

        this.$parent.$on('action:click', () => {
            this.visible = false;
        });
    },

    mounted() {
        window.addEventListener('click', this.closeDropdown);
    },

    beforeDestroy() {
        this.visible = false;
        this.removePopper();
    },

    destroyed() {
        window.removeEventListener('click', this.closeDropdown);
    },

    methods: {
        toggleDropdown() {
            this.visible = !this.visible;
        },

        showMenu() {
            if (this.disabled) {
                return;
            }

            const element = this.$el;
            this.initPopper(element);
        },

        hideMenu() {
            this.$root.$emit('hidden');
            this.removePopper();
        },

        initPopper(element) {
            this.removePopper();
            this._popper = new createPopper(element, this.$refs.menu, {
                placement: this.placement
            });
        },

        removePopper() {
            if (this._popper) {
                // Ensure popper event listeners are removed cleanly
                this._popper.destroy();
            }
            this._popper = null;
        },

        closeDropdown(e) {
            if (!this.$el || this.elementContains(this.$el, e.target) ||
                    !this._popper || this.elementContains(this._popper, e.target)
            ) {
                return;
            }

            this.visible = false;
        },

        elementContains(elm, otherElm) {
            if (typeof elm.contains === 'function') {
                return elm.contains(otherElm);
            }

            return false;
        }
    }
};
</script>

<style>
.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    min-width: 160px;
    padding: 5px 0;
    font-size: 14px;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #e2e2e2;
    border-radius: 3px;
    -o-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    transition: all .3s ease;
    opacity: 0;
    visibility: hidden;
    text-align: left;
}
.dropdown-menu li {
    margin-bottom: 0;
    text-align: left;
}
.wk-addon-wperp-table .dropdown-popper ul li a {
    line-height: 22px;
}
.dropdown-menu a {
    display: block;
    padding: 7px 18px;
    color: #23282d;
    white-space: nowrap;
    text-decoration: none;
    background-color: transparent;
}
.dropdown-menu a i {
    font-size: 18px;
    color: #d7dee2;
    margin-right: 9px;
}
.wk-addon-wperp-table .dropdown-popper {
	 position: absolute;
	 background: #fff;
	 color: black;
	 border-radius: 3px;
	 padding: 0;
	 text-align: center;
	 opacity: 0;
	 z-index: 99999;
	 left: -9999px;
	 transition: none !important;
}
 .wk-addon-wperp-table .dropdown-popper:after {
	 border-bottom-color: #f9f9f9;
}
 .wk-addon-wperp-table .dropdown-popper.show {
	 left: 0;
	 opacity: 1;
	 visibility: visible;
}
 .wk-addon-wperp-table .dropdown-popper ul {
	 margin: 0;
	 padding: 5px 0;
}
 .wk-addon-wperp-table .dropdown-popper ul li a {
	 line-height: 22px;
}
 .wk-addon-wperp-table .dropdown-popper .dropdown-popper.dropdown-menu {
	 border-color: #e2e2e2;
}
 .wk-addon-wperp-table .dropdown-popper .popper__arrow {
	 width: 0;
	 height: 0;
	 border-style: solid;
	 position: absolute;
	 margin: 5px;
	 border-color: #fff;
}
 .wk-addon-wperp-table .dropdown-popper .popper__arrow:after {
	 position: absolute;
	 content: '';
	 z-index: -1;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="top"] {
	 margin-bottom: 7px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="top"] .popper__arrow {
	 border-width: 7px 7px 0 7px;
	 border-left-color: transparent;
	 border-right-color: transparent;
	 border-bottom-color: transparent;
	 bottom: -7px;
	 left: calc(43%);
	 margin-top: 0;
	 margin-bottom: 0;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="top"] .popper__arrow:after {
	 border-top: 8px solid #e2e2e2;
	 border-left: 8px solid transparent;
	 border-right: 8px solid transparent;
	 left: -8px;
	 top: -7px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="bottom"] {
	 margin-top: 7px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="bottom"] .popper__arrow {
	 border-width: 0 7px 7px 7px;
	 border-left-color: transparent;
	 border-right-color: transparent;
	 border-top-color: transparent;
	 top: -7px;
	 left: calc(45%);
	 margin-top: 0;
	 margin-bottom: 0;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="bottom"] .popper__arrow:after {
	 border-bottom: 8px solid #e2e2e2;
	 border-left: 8px solid transparent;
	 border-right: 8px solid transparent;
	 left: -8px;
	 top: -1px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="right"] {
	 margin-left: 7px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="right"] .popper__arrow {
	 border-width: 7px 7px 7px 0;
	 border-left-color: transparent;
	 border-top-color: transparent;
	 border-bottom-color: transparent;
	 left: -7px;
	 top: calc(43%);
	 margin-left: 0;
	 margin-right: 0;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="right"] .popper__arrow:after {
	 border-right: 8px solid #e2e2e2;
	 border-top: 8px solid transparent;
	 border-bottom: 8px solid transparent;
	 right: -7px;
	 top: -8px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="left"] {
	 margin-right: 7px;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="left"] .popper__arrow {
	 border-width: 7px 0 7px 7px;
	 border-top-color: transparent;
	 border-right-color: transparent;
	 border-bottom-color: transparent;
	 right: -7px;
	 top: calc(43%);
	 margin-left: 0;
	 margin-right: 0;
}
 .wk-addon-wperp-table .dropdown-popper[x-placement^="left"] .popper__arrow:after {
	 border-left: 8px solid #e2e2e2;
	 border-top: 8px solid transparent;
	 border-bottom: 8px solid transparent;
	 left: -7px;
	 top: -8px;
}
</style>
