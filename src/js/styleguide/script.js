var Button, Styleguide, styleguide,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

Button = (function() {
  function Button() {
    console.log('button');
  }

  return Button;

})();

Styleguide = (function() {
  function Styleguide() {
    this._initComponents = bind(this._initComponents, this);
    this._initComponents();
  }

  Styleguide.prototype._initComponents = function() {
    return this.button = new Button();
  };

  return Styleguide;

})();

styleguide = new Styleguide;
