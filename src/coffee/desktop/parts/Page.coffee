class Page

	constructor: () ->

		name = @.constructor.name
		@container = $('#part-' + name.charAt(0).toLowerCase() + name.slice(1) )

		@_initContent()
		@_initEvents()


	# -----------------------------------------------------------------------------o private

	_initContent: () =>

		@screensContainer = @container.find('.screens')

		if @screensContainer.length == 1
			@screensEnabled = true
			@viewport = @screensContainer.children('.viewport')
			@screens = @screensContainer.find('.screen')

			@currentScreenIndex = 0
			@screensNumber = @screens.length

			@pItems = []
			@container.find('.pItem').each (key, elm) =>
				item = {}
				item.elm = $(elm)
				item.top = item.elm.position().top
				item.left = item.elm.position().left

				item.parentTop = item.elm.parents('.screen').position().top
				item.parentLeft = item.elm.parents('.screen').position().left

				item.transform = item.elm.attr('data-parallax')
				item.perc = 0

				@pItems.push(item)




			@deltaMax = null
			@scrolling = false

			@pos = {
				current: 0
				dest: 0
				prev: 0
				perc: 0
				max: @viewport.height() - W.wh
			}

			@ease = 0.05


	_initEvents: () =>

		if @screensEnabled == true
			$('body')
				.on('mousewheel', @_onMouseWheel)


	_prevScreen: () =>

		if @currentScreenIndex > 0
			@_goToScreen(@currentScreenIndex - 1)


	_nextScreen: () =>

		if @currentScreenIndex < @screensNumber - 1
			@_goToScreen(@currentScreenIndex + 1)


	_goToScreen: (index) =>

		@currentScreenIndex = index

		@pos.dest = @currentScreenIndex * W.wh



	# -----------------------------------------------------------------------------o listeners

	_onMouseWheel: (e) =>

		if Math.abs(e.deltaY) > 30

			if @deltaMax == null
				@deltaMax = Math.abs(e.deltaY)
				if e.deltaY < - 30
					@_nextScreen()
				else
					@_prevScreen()
			else if Math.abs(e.deltaY) > @deltaMax
				@deltaMax = Math.abs(e.deltaY)
		else if @deltaMax != null
			@deltaMax = null
		
		e.preventDefault()



	# -----------------------------------------------------------------------------o public

	resize: () =>

		if @screensEnabled
			@pos.max = @viewport.height() - W.wh

			if @pItems.length
				$.each @pItems, (key, item) =>
					item.top = item.elm.position().top
					item.left = item.elm.position().left

					item.parentTop = item.elm.parents('.screen').position().top
					item.parentLeft = item.elm.parents('.screen').position().left


	update: () =>

		if @screensEnabled
			@pos.prev = @pos.current
			@pos.current += (@pos.dest - @pos.current) * @ease

			if @pos.max > 0
				@pos.perc = (@pos.current) / @pos.max

			if ~~ @pos.prev != ~~ @pos.current
				Normalize.transform(@viewport[0], 'translate3d(0, -' + @pos.current + 'px, 0)')

				$.each @pItems, (key, item) =>

					if item.parentTop - W.ww < @pos.current < item.parentTop + W.ww
						item.perc = (item.parentTop - @pos.current) / @pos.max

						transform = item.transform
						vals = transform.match(/[^{}]+(?=\})/g)

						$.each vals, (keyVal, val) =>
							transformVal = item.perc * W.wh
							if val.substr(0, 1) == '-'
								transformVal *= -1
							transform = transform.replace('{' + (val) + '}', transformVal)



						Normalize.transform(item.elm[0], transform)



