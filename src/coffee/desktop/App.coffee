$				= require 'jquery'
App 			= require 'core/App'

$ ->
	
	app = new App()

	console.log app

	(tick = () ->
		app.update()
		window.requestAnimationFrame(tick)
	)()