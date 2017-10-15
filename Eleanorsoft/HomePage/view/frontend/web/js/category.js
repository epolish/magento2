define(['jquery', 'uiComponent', 'Magento_Customer/js/customer-data'], function($, Component, customerData) {

    return Component.extend({
        loader: eleanorsoftHomepagePreloaderPath,
        slider: '.categories-slider',
        action: 'eleanorsoft/homepage/category',
        slickSettings: {
            dots: true,
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            variableWidth: true
        },
        initialize: function () {
            self = this;
            this._super();
            this.categories;
            this.selectedCategory;
            this.observe(['categories', 'selectedCategory']);

            $.getJSON(self.action, function(data) {
                var categories = JSON.parse(data);

                categories = $.each(categories, function (key, value) {
                    value['parent'] = self;
                });
                self.categories(categories);
                self.selectedCategory(self.categories()[0]);
                $(self.slider).slick(self.slickSettings);
                self.toggleActive(self.selectedCategory());
            });
        },
        refreshWithSlider: function (proc) {
            $(self.slider).slick('unslick');
            proc();
            $(self.slider).slick(self.slickSettings);
        },
        formatCurrency: function (value) {
            if (value) {
                var price = parseFloat(value);

                return '$' + (price ? price.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) : '');
            } else {
                return;
            }
        },
        getSavedInfo: function (msrp, price) {
            if (msrp) {
                var savedMoney = msrp - price,
                    savedPercent = (savedMoney / msrp) * 100;

                savedPercent = parseInt(savedPercent) ?  Math.round(savedPercent): '';

                return self.formatCurrency(savedMoney) + ' (' + Math.round(savedPercent) + '%)';
            } else {
                return;
            }
        },
        toggleActive: function (category) {
            if (category.products.length < 4 && category.isLoaded == undefined) {
                self.refreshWithSlider(function () {
                    self.selectedCategory(category);
                });

                category['isLoaded'] = false;

                $.getJSON(self.action + '/id/' + category.id, function (data) {
                    self.refreshWithSlider(function () {
                        category['isLoaded'] = true;
                        category.products = category.products.concat(JSON.parse(data));

                        if (self.selectedCategory().id == category.id) {
                            self.selectedCategory(category);
                        }
                    });
                });
            } else {
                self.refreshWithSlider(function () {
                    self.selectedCategory(category);
                });
            }
        },
        addToCart: function (data, element) {
            var status = $(element).data('status');

            if (status == 'processing' || status == 'added') {
                return;
            }

            $(element).text('Processing...');
            $(element).attr('data-status', 'processing');
            $.post(
                data.addToCartUrl,
                {
                    product: data.id,
                    form_key: $('[name="form_key"]').val()
                }
            ).done(function (response) {
                $(element).text('Added');
                $(element).attr('data-status', 'added');
                customerData.invalidate(['cart']);
            }).fail(function (jqXHR, textStatus) {
                console.log('Couldn`t add product to cart. ' + textStatus);
            });

        }
    });
});
