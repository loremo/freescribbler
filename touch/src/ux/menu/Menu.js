/**
 * License: The MIT License
 * Copyright (c) 2013 ClearCode Inc.
 *
 * Description:
 *   Simple dropdown menu component for Sencha Touch 2.
 *   This works like:
 *     [Anchor button]
 *     __/\____
 *     |Item 1|
 *     |Item 2|
 *     |Item 3|
 *     ~~~~~~~~
 *
 * Usage:
 *   var button = Ext.getCmp('ext-something-1');
 *   Ext.ux.menu.Menu.open(
 *     button, // the anchor
 *     [
 *       { text: 'Item 1', value: 'value1' },
 *       { text: 'Item 2', value: 'value2' },
 *       { text: 'Item 3', value: 'value3' }
 *     ],
 *     function(value) { // callback (called after the menu is closed)
 *       // The value will be 'value1', 'value2', or 'value3'.
 *       // If you close the menu by tapping on the mask, it becomes null.
 *     }
 *   );
 *
 * Recommended styles:
 *   .x-popup-menu {
 *     box-shadow: 0 0 0.5em;
 *     border-radius: 0.3em;
 *     color: $list-color;
 *     background: $list-bg-color;
 *   }
 *   .x-popup-menu .x-anchor.x-anchor-left,
 *   .x-popup-menu .x-anchor.x-anchor-right,
 *   .x-popup-menu .x-anchor.x-anchor-top,
 *   .x-popup-menu .x-anchor.x-anchor-bottom {
 *     background: $list-bg-color;
 *   }
 *   .x-popup-menu .x-button-label {
 *     text-align: left;
 *   }
 */
Ext.define('Ext.ux.menu.Menu', {
  extend:   'Ext.ActionSheet',
  xtype:    'menu',
  requires: [
    'Ext.ActionSheet'
  ],

  statics: {
    open: function(owner, items, callback) {
      var menu = Ext.Viewport.add({
        xtype:     'menu',
        defaults:  {
          xtype:   'button',
          ui:      'plain',
          handler: function(button) {
            menu.hide();
            callback(button.config.value);
          }
        },
        items:     items,
        listeners: {
          hide: function() {
            Ext.Viewport.remove(menu);
          }
        }
      });
      menu.prepare();
      menu.showBy(owner);
    }
  },

  config: {
    cls:           Ext.baseCSSPrefix + 'popup-menu',
    hideOnMaskTap: true,
    showAnimation: {
      type:     'fadeIn',
      duration: 200,
      easing:   'ease-out'
    },
    hideAnimation: {
      type:     'fadeOut',
      duration: 200,
      easing:   'ease-out'
    },
    extraSidePadding: 50
  },

  prepare: function() {
    var me = this;
    var buttons = this.query('button');
    var sidePadding = this.element.getWidth() - buttons[0].element.getWidth();
    var maxWidth = 0;
    buttons.forEach(function(button) {
      var width = me.getTextWidth(button.textElement);
      if (width > maxWidth)
        maxWidth = width;
    });
    this.setWidth(maxWidth + sidePadding + this.config.extraSidePadding);

    // The height of the panel is enough to show all contents by defaylt.
    // Before it is expanded automatically, I save the calculated height.
    this.setHeight(this.element.getHeight());
  },

  // Text labels in buttons are defined as <span style="display:block">text</span>
  // so I have to calculate actual width of the text with inserted elements.
  getTextWidth: function(span) {
    var leftAnchor = Ext.dom.Element.create({
      tag:   'span',
      style: 'display: inline !important;',
      html:  '!'
    });
    span.insertFirst(leftAnchor);
    var rightAnchor = Ext.dom.Element.create({
      tag:   'span',
      style: 'display: inline !important;',
      html:  '!'
    });
    span.append(rightAnchor);
    var left = leftAnchor.getX();
    var right = rightAnchor.getX();
    span.removeChild(leftAnchor);
    span.removeChild(rightAnchor);
    return Math.abs(right - left);
  }
});