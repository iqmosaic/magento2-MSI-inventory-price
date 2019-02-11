define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Iqmosaic_InventoryPrice/product/grid/cell/price-source-items.html'
        },

        /**
         * Get source items price data (source name and qty)
         *
         * @param {Object} record - Record object
         * @returns {Array} Result array
         */
        getSourceItemsData: function (record) {
            return record[this.index] ? record[this.index] : [];
        }
    });
});
