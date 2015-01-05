class Event

	@MOUSEDOWN = if $('body').hasClass('tablet') then 'touchstart' else 'mousedown'
	@MOUSEUP = if $('body').hasClass('tablet') then 'touchend' else 'mouseup'
	@MOUSEMOVE = if $('body').hasClass('tablet') then 'touchmove' else 'mousemove'
	@CLICK = if $('body').hasClass('tablet') then 'touchstart' else 'click'
	@ENTER = if $('body').hasClass('tablet') then 'touchstart' else 'mouseenter'

	@KEYDOWN = 'keydown'

	@WHEEL = 'mousewheel'

	@LOADED = 'loaded'
	@STEPS = 'steps'

	@SUBMIT = 'submit'