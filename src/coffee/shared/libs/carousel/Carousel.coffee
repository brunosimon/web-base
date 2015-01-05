class Carousel

	constructor: (options) ->

		{@container, @delay, @onUpdate} = options

		@_initContent()
		@_initEvents()


	# -----------------------------------------------------------------------------o private

	_initContent: () ->

		@list = @container.find('.slides')
		@slides = @list.find('li')
		@nav = @container.find('nav')
		@navItems = @nav.find('li')

		@timer = undefined
		@delay = @delay || 5000
		@currentSlideIndex = 0
		@prevSlideIndex = 0
		@slidesLength = @slides.length

		@itemWidth = @slides.width()
		@slides.addClass('no-transition right')
		@slides.eq(0).removeClass('right')
		@navItems.eq(0).addClass('active')

		@_getSlide()


	_initEvents: () ->

		@navItems
			.on(Event.CLICK, @_onNavClick)


	_getSlide: () ->

		@timer = setTimeout () =>
			@prevSlideIndex = @currentSlideIndex
			@currentSlideIndex++

			if @currentSlideIndex > @slidesLength - 1
				@currentSlideIndex = 0
			else if @currentSlideIndex < 0
				@currentSlideIndex = @slidesLength - 1

			@_updateSlides()

		, @delay


	_updateSlides: (manual) ->

		direction = ['left', 'right']

		if manual == true && @prevSlideIndex > @currentSlideIndex
			direction.reverse()

		@slides.eq(@currentSlideIndex).removeClass('left right').addClass('no-transition ' + direction[1])
		@slides.eq(@currentSlideIndex)[0].offsetHeight

		@slides.eq(@currentSlideIndex).removeClass('no-transition')
		@slides.eq(@currentSlideIndex)[0].offsetHeight
		@slides.eq(@currentSlideIndex).removeClass(direction[1])

		@slides.eq(@prevSlideIndex).removeClass('no-transition').addClass(direction[0])

		@navItems.eq(@currentSlideIndex).addClass('active').siblings().removeClass('active')

		@_getSlide()

		if @onUpdate
			@onUpdate({'prev': @prevSlideIndex, 'current': @currentSlideIndex})


	# -----------------------------------------------------------------------------o listeners

	_onNavClick: (e) =>

		index = $(e.currentTarget).index()

		if index != @currentSlideIndex
			@prevSlideIndex = @currentSlideIndex
			@currentSlideIndex = index
			clearTimeout(@timer)
			@_updateSlides(true)


