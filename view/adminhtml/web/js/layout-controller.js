/**
 * @api
 */
define([
    'Magento_Ui/js/form/element/abstract',
    'underscore',
    'mage/translate',
	'jquery'
], function (AbstractField, _, __, $) {
    'use strict';

    return AbstractField.extend({
        defaults: {
            template: 'ui/form/components/single/field',

            listens: { }
        },

        /**
         * @inheritdoc
         */
        initConfig: function (config) {
            this._super();

			console.log('omg, created, so awesome', this)

            return this;
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            return this
                ._super()
                .observe('checked');
        },

        /**
         * @inheritdoc
         */
        setInitialValue: function () {
			console.log('does nothing yet')
			this.on('value', this.onUpdate.bind(this));

            return this;
        },

        /**
         * @inheritdoc
         */
        onUpdate: function () {
            if (this.hasUnique) {
                this.setUnique();
            }

            return this._super();
        },

        /**
         * @inheritdoc
         */
        reset: function () {
            this.value(this.initialValue);

            this.error(false);

            return this;
        },

        /**
         * @inheritdoc
         */
        clear: function () {
            //this.value('');

            this.error(false);

            return this;
        }
    });
});
