$ = require 'jquery'

module.exports = class W

	@init: () ->

		W.window = $(window)
		W.body = $('body')

		W.device = $('body').attr('class')

		W.ww = $(window).width()
		W.wh = $(window).height()
		W.sw = screen.width
		W.sh = screen.height

		W.scrollTop = {
			real: 0
			calc: 0
		}

		W.isTablet = if $('body').hasClass('tablet') then true else false