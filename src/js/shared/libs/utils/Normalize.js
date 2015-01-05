Normalize = function() {};

Normalize.init = function()
{
	//TODO 
}

Normalize.touchEvent = function(event) 
{
	if (event.pageX == null && original.clientX != null) 
	{
        event.pageX = original.clientX + ( doc && doc.scrollLeft || body && body.scrollLeft || 0 ) - ( doc && doc.clientLeft || body && body.clientLeft || 0 );
        event.pageY = original.clientY + ( doc && doc.scrollTop  || body && body.scrollTop  || 0 ) - ( doc && doc.clientTop  || body && body.clientTop  || 0 );
    }
};

Normalize.transform = function(dom, transform)
{
	if(dom) 
	{
		dom.style.transform = transform;
		dom.style.webkitTransform = transform;
		dom.style.mozTransform = transform;
		
	}
};

Normalize.transformOrigin = function(dom, origin)
{
	if(dom) 
	{
		dom.style.transformOrigin = origin;
		dom.style.webkitTransformOrigin = origin;
		dom.style.mozTransformOrigin = origin;
	}
};

Normalize.transition = function(dom, transition)
{
	if(dom) {
		dom.style.transition = transition;
		dom.style.webkitTransition = transition;
		dom.style.mozTransition = transition;
	}
};