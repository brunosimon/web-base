$ = require 'jquery'

module.exports = class Loader

	constructor: (options) ->

		{@container, @each, @complete} = options
	
		pics = @container.find('img').filter( () ->
			return this.getAttribute('src') == ''
		)
			
		@imgLength = pics.length
		@imgInc = 0
		@steps = 0
		@empty = false

		if !pics.length
			@empty = true
			if @complete
				@complete()

		pics.each (key, item) =>

			src = item.getAttribute('data-src')

			if img.complete
				@_onLoad(item)
			else
				item.onload = @_onLoad(item)

			item.src = src




	_onLoad: (item) =>

		@imgInc++
		@steps = @imgInc / @imgLength * 100
		
		if @each
			@each(item)

		if @imgInc == @imgLength

			if @complete
				@complete()