<!-- Modal -->
<div class="modal fade" id="panduan" tabindex="-1" aria-labelledby="panduan" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title fw-bold" id="exampleModalLabel">Panduan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="">
					<img src="<?php echo base_url();?>assets/landing/img/panduandh3.jpg" alt="" class="img-fluid img-rounded mx-auto">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<footer class="footer">
	<div class="text-center text-white">
		<div class="footer-logo">
			<p>&copy; Copyrights Pusdatinrenbang Kementerian PPN/Bappenas</p>
		</div>
	</div>
</footer><!-- End  Footer -->
<script type="text/javascript">
	!function ($) {

		"use strict"; // jshint ;_;

		/* MAGNIFY PUBLIC CLASS DEFINITION
			* =============================== */

		var Magnify = function (element, options) {
			this.init('magnify', element, options)
		}

		Magnify.prototype = {

			constructor: Magnify

			, init: function (type, element, options) {
				var event = 'mousemove'
					, eventOut = 'mouseleave';

				this.type = type
				this.$element = $(element)
				this.options = this.getOptions(options)
				this.nativeWidth = 0
				this.nativeHeight = 0

				this.$element.wrap('<div class="magnify" >');
				this.$element.parent('.magnify').append('<div class="magnify-large" >');
				this.$element.siblings(".magnify-large").css("background", "url('" + this.$element.attr("src") + "') no-repeat");

				this.$element.parent('.magnify').on(event + '.' + this.type, $.proxy(this.check, this));
				this.$element.parent('.magnify').on(eventOut + '.' + this.type, $.proxy(this.check, this));
			}

			, getOptions: function (options) {
				options = $.extend({}, $.fn[this.type].defaults, options, this.$element.data())

				if (options.delay && typeof options.delay == 'number') {
					options.delay = {
						show: options.delay
						, hide: options.delay
					}
				}

				return options
			}

			, check: function (e) {
				var container = $(e.currentTarget);
				var self = container.children('img');
				var mag = container.children(".magnify-large");

				// Get the native dimensions of the image
				if (!this.nativeWidth && !this.nativeHeight) {
					var image = new Image();
					image.src = self.attr("src");

					this.nativeWidth = image.width;
					this.nativeHeight = image.height;

				} else {

					var magnifyOffset = container.offset();
					var mx = e.pageX - magnifyOffset.left;
					var my = e.pageY - magnifyOffset.top;

					if (mx < container.width() && my < container.height() && mx > 0 && my > 0) {
						mag.fadeIn(100);
					} else {
						mag.fadeOut(100);
					}

					if (mag.is(":visible")) {
						var rx = Math.round(mx / container.width() * this.nativeWidth - mag.width() / 2) * -1;
						var ry = Math.round(my / container.height() * this.nativeHeight - mag.height() / 2) * -1;
						var bgp = rx + "px " + ry + "px";

						var px = mx - mag.width() / 2;
						var py = my - mag.height() / 2;

						mag.css({ left: px, top: py, backgroundPosition: bgp });
					}
				}

			}
		}


		/* MAGNIFY PLUGIN DEFINITION
			* ========================= */

		$.fn.magnify = function (option) {
			return this.each(function () {
				var $this = $(this)
					, data = $this.data('magnify')
					, options = typeof option == 'object' && option
				if (!data) $this.data('tooltip', (data = new Magnify(this, options)))
				if (typeof option == 'string') data[option]()
			})
		}

		$.fn.magnify.Constructor = Magnify

		$.fn.magnify.defaults = {
			delay: 0
		}


		/* MAGNIFY DATA-API
			* ================ */

		$(window).on('load', function () {
			$('[data-toggle="magnify"]').each(function () {
				var $mag = $(this);
				$mag.magnify()
			})
		})

	}(window.jQuery);
</script>