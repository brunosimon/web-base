var App, AppCore, Event, Home, Loader, Normalize, Page, Router, SocialSharing, Transitions, Utils, W,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

AppCore = (function() {
  function AppCore() {
    this.update = bind(this.update, this);
    this._onTransitionsEnd = bind(this._onTransitionsEnd, this);
    this._onTransitionsMiddle = bind(this._onTransitionsMiddle, this);
    this._onTransitionsStart = bind(this._onTransitionsStart, this);
    this._onResize = bind(this._onResize, this);
    this._initEvents = bind(this._initEvents, this);
    this._destroySection = bind(this._destroySection, this);
    this._initPage = bind(this._initPage, this);
    this._initContent = bind(this._initContent, this);
    console.log('%c# --------------------o Running Desktop', 'background: #42e34d; color: #0F0F0F;');
    W.init();
    this._initContent();
    this._initEvents();
    this._onResize();
  }

  AppCore.prototype._initContent = function() {
    W.time = {
      now: +new Date(),
      old: +new Date()
    };
    this.transitions = new Transitions();
    return this._initPage();
  };

  AppCore.prototype._initPage = function() {
    var Class;
    this._destroySection();
    this.pageId = Router.singleton.pages.current.replace('part-', '');
    console.log(this.pageId);
    if (this.pageId) {
      Class = App.pages[this.pageId] || Page;
      this.section = new Class({
        pageId: this.pageId
      });
    }
    return this._onResize();
  };

  AppCore.prototype._destroySection = function() {
    if (this.section) {
      this.section.destroy();
    }
    return this.section = void 0;
  };

  AppCore.prototype._initEvents = function() {
    W.window.on('resize', this._onResize);
    return $(this.transitions).on(Transitions.START, this._onTransitionsStart).on(Transitions.MIDDLE, this._onTransitionsMiddle).on(Transitions.END, this._onTransitionsEnd);
  };

  AppCore.prototype._onResize = function() {
    W.sw = screen.width;
    W.sh = screen.height;
    W.ww = W.window.width();
    W.wh = W.window.height();
    if (this.section && this.section.resize) {
      return this.section.resize();
    }
  };

  AppCore.prototype._onTransitionsStart = function() {};

  AppCore.prototype._onTransitionsMiddle = function() {
    return this._initPage();
  };

  AppCore.prototype._onTransitionsEnd = function() {};

  AppCore.prototype.update = function() {
    W.time.now = +new Date();
    W.time.delta = (W.time.now - W.time.old) / 1000;
    W.time.old = W.time.now;
    if (this.section && this.section.update) {
      return this.section.update();
    }
  };

  return AppCore;

})();

$(function() {
  var app, tick;
  app = new App();
  return (tick = function() {
    app.update();
    return window.requestAnimationFrame(tick);
  })();
});

Event = (function() {
  function Event() {}

  Event.MOUSEDOWN = $('body').hasClass('tablet') ? 'touchstart' : 'mousedown';

  Event.MOUSEUP = $('body').hasClass('tablet') ? 'touchend' : 'mouseup';

  Event.MOUSEMOVE = $('body').hasClass('tablet') ? 'touchmove' : 'mousemove';

  Event.CLICK = $('body').hasClass('tablet') ? 'touchstart' : 'click';

  Event.ENTER = $('body').hasClass('tablet') ? 'touchstart' : 'mouseenter';

  Event.KEYDOWN = 'keydown';

  Event.WHEEL = 'mousewheel';

  Event.LOADED = 'loaded';

  Event.STEPS = 'steps';

  Event.SUBMIT = 'submit';

  return Event;

})();

Loader = (function() {
  function Loader(options) {
    this._onLoad = bind(this._onLoad, this);
    var pics;
    this.container = options.container, this.each = options.each, this.complete = options.complete;
    pics = this.container.find('img').filter(function() {
      return this.getAttribute('src') === '';
    });
    this.imgLength = pics.length;
    this.imgInc = 0;
    this.steps = 0;
    this.empty = false;
    if (!pics.length) {
      this.empty = true;
      if (this.complete) {
        this.complete();
      }
    }
    pics.each((function(_this) {
      return function(key, item) {
        var src;
        src = item.getAttribute('data-src');
        if (img.complete) {
          _this._onLoad(item);
        } else {
          item.onload = _this._onLoad(item);
        }
        return item.src = src;
      };
    })(this));
  }

  Loader.prototype._onLoad = function(item) {
    this.imgInc++;
    this.steps = this.imgInc / this.imgLength * 100;
    if (this.each) {
      this.each(item);
    }
    if (this.imgInc === this.imgLength) {
      if (this.complete) {
        return this.complete();
      }
    }
  };

  return Loader;

})();

Page = (function() {
  function Page(options) {
    this.destroy = bind(this.destroy, this);
    this.update = bind(this.update, this);
    this.resize = bind(this.resize, this);
    this._initEvents = bind(this._initEvents, this);
    this._initContent = bind(this._initContent, this);
    this.pageId = options.pageId;
    console.log('%c# --------------------o Initialize Class ' + this.pageId, 'background: #e1e342; color: #0F0F0F;');
    this._initContent();
    this._initEvents();
  }

  Page.prototype._initContent = function() {
    this.container = $('#part-' + this.pageId.charAt(0).toLowerCase() + this.pageId.slice(1));
    return new Loader({
      container: this.container
    });
  };

  Page.prototype._initEvents = function() {};

  Page.prototype.resize = function() {};

  Page.prototype.update = function() {};

  Page.prototype.destroy = function() {
    var name;
    name = this.constructor.name;
    return console.log('%c# --------------------o Destroy Class ' + name, 'background: #e3b042; color: #0F0F0F;');
  };

  return Page;

})();

Router = (function() {
  Router.CALLSTART = 'callstart';

  Router.CALLEND = 'callend';

  Router.CALLERROR = 'callerror';

  Router.INITIALIZE = 'initialize';

  Router.CLICK = 'click';

  Router.singleton;

  function Router() {
    this.backCall = bind(this.backCall, this);
    this.cache = {};
    this.container = $('.ajaxContainer');
    this.current = this.container.attr('id');
    this.headTitle = $('title');
    this.pages = {
      'prev': '',
      'current': this.container.attr('id')
    };
    this.requestInProgress = false;
    this.fromCache = false;
    this.fromNativeNav = false;
    this._initEvents();
    this.initCache();
    Router.singleton = this;
  }

  Router.prototype.initCache = function() {
    this.href = document.location.pathname;
    this.content = this.container;
    return this.caching();
  };

  Router.prototype._initEvents = function() {
    $(document).on('click', 'a', (function(_this) {
      return function(e) {
        _this.elm = $(e.currentTarget);
        _this.href = _this.elm.attr('href');
        _this.checkRequestAvailability();
        if (_this.isRequestAvailable) {
          _this.getContent();
        }
        if (!_this.isTargetSet) {
          e.preventDefault();
        }
        return $(_this).trigger(Router.CLICK);
      };
    })(this));
    return $(window).on('popstate', (function(_this) {
      return function(event) {
        if (document.location.pathname.split('#')[0] !== _this.href) {
          _this.backCall();
          return event.preventDefault();
        }
      };
    })(this));
  };

  Router.prototype.checkRequestAvailability = function() {
    this.isRequestAvailable = true;
    this.isTargetSet = false;
    if (this.areUrlsMatching()) {
      this.isRequestAvailable = false;
    }
    if (this.requestInProgress) {
      this.isRequestAvailable = false;
    }
    if (this.elm.attr('target')) {
      this.isTargetSet = true;
      return this.isRequestAvailable = false;
    }
  };

  Router.prototype.areUrlsMatching = function() {
    var currentPath, currentUrl, urlToCheck;
    urlToCheck = this.href;
    currentPath = document.location.pathname;
    currentUrl = document.location.href;
    if (urlToCheck.substr(-1) === '/') {
      urlToCheck = urlToCheck.substr(0, urlToCheck.length - 1);
    }
    if (currentUrl.substr(-1) === '/') {
      currentUrl = currentUrl.substr(0, currentUrl.length - 1);
      currentPath = currentPath.substr(0, currentPath.length - 1);
    }
    if (urlToCheck === currentPath || urlToCheck === currentUrl) {
      return true;
    }
    return false;
  };

  Router.prototype.backCall = function() {
    this.fromNativeNav = true;
    if (document.location.pathname === this.href) {
      return window.history.go(-1);
    } else {
      this.href = document.location.pathname;
      return this.getContent();
    }
  };

  Router.prototype.getContent = function() {
    this.pages.prev = this.pages.current;
    this.requestInProgress = true;
    $(this).trigger(Router.CALLSTART);
    if (this.cache[this.href]) {
      this.fromCache = true;
      this.content = this.cache[this.href].content.clone();
      this.title = this.cache[this.href].title;
      return this.requestSucceeded();
    } else {
      this.fromCache = false;
      return this.request();
    }
  };

  Router.prototype.request = function() {
    if (this.ajaxRequest && this.ajaxRequest !== 4) {
      this.ajaxRequest.abort();
    }
    return this.ajaxRequest = $.ajax({
      url: this.href,
      success: (function(_this) {
        return function(response) {
          _this.ajaxResponse = response;
          _this.content = $(response).filter('.ajaxContainer');
          if (_this.content.length === 0) {
            _this.content = $(response).find('.ajaxContainer');
          }
          _this.title = $(response).filter('title').text();
          return _this.requestSucceeded();
        };
      })(this),
      complete: (function(_this) {
        return function(request, status) {};
      })(this),
      error: (function(_this) {
        return function(response) {
          return $(_this).trigger(Router.CALLERROR);
        };
      })(this)
    });
  };

  Router.prototype.requestSucceeded = function(response) {
    this.pages.current = this.content.attr('id');
    this.changeTitle();
    this.caching();
    if (this.fromNativeNav === false) {
      this.changeUrl();
    }
    this.fromNativeNav = false;
    return $(this).trigger(Router.CALLEND);
  };

  Router.prototype.changeTitle = function() {
    return this.headTitle.text(this.title);
  };

  Router.prototype.caching = function() {
    return this.cache[this.href] = {
      'content': this.content.clone(),
      'title': this.title
    };
  };

  Router.prototype.changeUrl = function(href) {
    var pathname, state;
    if (href) {
      this.href = href;
    }
    state = {};
    pathname = this.href.split(window.location.host)[1];
    if (pathname) {
      pathname = pathname.substr(4);
    }
    if (window.history.pushState) {
      if (this.pages.current === this.pages.prev) {
        return window.history.replaceState(state, null, this.href);
      } else {
        return window.history.pushState(state, null, this.href);
      }
    } else {
      return window.location.hash = pathname;
    }
  };

  return Router;

})();

SocialSharing = (function() {
  function SocialSharing() {
    this._onLinkClick = bind(this._onLinkClick, this);
    this._initEvents = bind(this._initEvents, this);
    this.links = $('.social-link');
    this._initEvents();
  }

  SocialSharing.prototype._initEvents = function() {
    return $(document).on(Event.CLICK, '.social-link', this._onLinkClick);
  };

  SocialSharing.prototype._onLinkClick = function(e) {
    var height, leftPosition, link, options, topPosition, width, windowFeatures;
    e.preventDefault();
    link = $(e.currentTarget).attr('href');
    width = 800;
    height = 500;
    leftPosition = (W.ww / 2) - ((width / 2) + 10);
    topPosition = (W.wh / 2) - ((height / 2) + 50);
    options = windowFeatures = "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
    return window.open(link, '', options);
  };

  return SocialSharing;

})();

Transitions = (function() {
  Transitions.START = 'callstart';

  Transitions.END = 'callend';

  Transitions.MIDDLE = 'callmiddle';

  function Transitions() {
    this._defaultIn = bind(this._defaultIn, this);
    this._defaultOut = bind(this._defaultOut, this);
    this._transitionIn = bind(this._transitionIn, this);
    this._transitionOut = bind(this._transitionOut, this);
    this._onRouterEnd = bind(this._onRouterEnd, this);
    this._onRouterStart = bind(this._onRouterStart, this);
    this._onRouterClick = bind(this._onRouterClick, this);
    this._initEvents = bind(this._initEvents, this);
    this.router = new Router();
    this._transitionInDelay = 0;
    this.transitionsWhenCallFinished = true;
    this._initEvents();
  }

  Transitions.prototype._initEvents = function() {
    return $(this.router).on(Router.CLICK, this._onRouterClick).on(Router.CALLSTART, this._onRouterStart).on(Router.CALLEND, this._onRouterEnd);
  };

  Transitions.prototype._onRouterClick = function() {};

  Transitions.prototype._onRouterStart = function() {
    if (this.transitionsWhenCallFinished !== true) {
      return this._transitionOut();
    }
  };

  Transitions.prototype._onRouterEnd = function() {
    var transitionName;
    if (this.transitionsWhenCallFinished === true) {
      transitionName = '_' + this.router.pages.prev + 'To' + this.router.pages.current.charAt(0).toUpperCase() + this.router.pages.current.slice(1);
      if (this[transitionName]) {
        return this[transitionName]();
      } else {
        this._transitionOut();
        return setTimeout((function(_this) {
          return function() {
            return _this._transitionIn();
          };
        })(this), this._transitionInDelay);
      }
    } else {
      return this._transitionIn();
    }
  };

  Transitions.prototype._transitionOut = function() {
    var transitionName;
    transitionName = '_' + this.router.pages.prev + 'Out';
    if (this[transitionName]) {
      return this[transitionName]();
    } else {
      return this._defaultOut();
    }
  };

  Transitions.prototype._transitionIn = function() {
    var transitionName;
    transitionName = '_' + this.router.pages.current + 'In';
    $(window).scrollTop(0);
    if (this[transitionName]) {
      return this[transitionName]();
    } else {
      return this._defaultIn();
    }
  };

  Transitions.prototype._defaultOut = function() {
    this.container = $('.ajaxContainer');
    this.router.requestInProgress = true;
    this.container.addClass('removed');
    this.container[0].offsetHeight;
    return $(this).trigger(Transitions.START);
  };

  Transitions.prototype._defaultIn = function() {
    var newContainer, oldContainer;
    oldContainer = $('.ajaxContainer');
    newContainer = this.router.content;
    oldContainer.eq(0).after(newContainer);
    oldContainer.remove();
    newContainer.addClass('added');
    newContainer[0].offsetHeight;
    newContainer.removeClass('added');
    this.sectionId = this.router.pages.current;
    $(this).trigger(Transitions.MIDDLE);
    this.router.requestInProgress = false;
    return $(this).trigger(Transitions.END);
  };

  return Transitions;

})();

W = (function() {
  function W() {}

  W.init = function() {
    W.window = $(window);
    W.body = $('body');
    W.device = $('body').attr('class');
    W.ww = $(window).width();
    W.wh = $(window).height();
    W.sw = screen.width;
    W.sh = screen.height;
    W.scrollTop = {
      real: 0,
      calc: 0
    };
    return W.isTablet = $('body').hasClass('tablet') ? true : false;
  };

  return W;

})();

Normalize = (function() {
  function Normalize() {}

  Normalize.transform = function(elm, transform) {
    if (elm) {
      elm.style.transform = transform;
      elm.style.webkitTransform = transform;
      return elm.style.mozTransform = transform;
    }
  };

  Normalize.transformOrigin = function(elm, origin) {
    if (elm) {
      elm.style.transformOrigin = transform;
      elm.style.webkitTransformOrigin = transform;
      return elm.style.mozTransformOrigin = transform;
    }
  };

  return Normalize;

})();

Utils = (function() {
  function Utils() {}

  Utils.getCoverSizeImage = function(picWidth, picHeight, containerWidth, containerHeight) {
    var ch, cr, cw, ph, pr, pw;
    pw = picWidth;
    ph = picHeight;
    cw = containerWidth || W.ww;
    ch = containerHeight || W.wh;
    pr = pw / ph;
    cr = cw / ch;
    if (cr < pr) {
      return {
        'width': ch * pr,
        'height': ch,
        'top': 0,
        'left': -((ch * pr) - cw) * 0.5
      };
    } else {
      return {
        'width': cw,
        'height': cw / pr,
        'top': -((cw / pr) - ch) * 0.5,
        'left': 0
      };
    }
  };

  Utils.getContainSizeImage = function(picWidth, picHeight, containerWidth, containerHeight) {
    var ch, cr, cw, ph, pr, pw;
    pw = picWidth;
    ph = picHeight;
    cw = containerWidth || W.ww;
    ch = containerHeight || W.wh;
    pr = pw / ph;
    cr = cw / ch;
    if (cr < pr) {
      return {
        'width': cw,
        'height': cw / pr,
        'top': (ch - cw / pr) * 0.5,
        'left': 0
      };
    } else {
      return {
        'width': ch * pr,
        'height': ch,
        'top': 0,
        'left': (cw - ch * pr) * 0.5
      };
    }
  };

  Utils.clearTimers = function(timers) {
    return $.each(timers, function(key, timer) {
      return clearTimeout(timer);
    });
  };

  Utils.hexToRgb = function(hex) {
    var result;
    result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    if (result) {
      return {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
      };
    } else {
      return null;
    }
  };

  return Utils;

})();

Home = (function(superClass) {
  extend(Home, superClass);

  function Home() {
    this._initContent = bind(this._initContent, this);
    Home.__super__.constructor.apply(this, arguments);
  }

  Home.prototype._initContent = function() {
    return Home.__super__._initContent.apply(this, arguments);
  };

  return Home;

})(Page);

App = (function(superClass) {
  extend(App, superClass);

  App.pages = {
    'home': Home
  };

  function App() {
    App.__super__.constructor.apply(this, arguments);
  }

  return App;

})(AppCore);
