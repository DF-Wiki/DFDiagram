$(function(){
	var dfd = {};
	
	var Cache = dfd.Cache = function() {
		var _cache = {};
		var self = {};
		self.get = function(k) {
			return _cache[k];
		};
		self.set = function(k, v){
			_cache[k] = v; return v;
		};
		
		return self;
	};

	
	var Character = dfd.Character = function(font, ch, fg, bg){
		
	};
	
	
	var FontCanvas = dfd.FontCanvas = function(font, image_url){
		var self = {};
		
		// Load font image - bmp = bitmap, but other formats are perfectly fine
		var bmp = self.bmp = $('<img>').attr('src', image_url);
		var bmp_canvas = self.bmp_canvas = $('<canvas>');
		bmp.ready(function(){
			var cv = bmp_canvas[0];
			cv.width = bmp[0].width;
			cv.height = bmp[0].height;
			cv.getContext('2d').drawImage(bmp[0], 0, 0);
		});
		
		return self;
	};
	
	var Font = dfd.Font = function(image_url){
		var self = {};
		
		self.font_canvas = FontCanvas(self, image_url)
		self.cache = Cache()
		
		self.get_char = function(ch, fg, bg){
			if (!fg) fg = 'rgb(255,255,255)';
			if (!bg) bg = 'rgb(0,0,0)';
			// Convert to rgb
			fg = $('<span>').css('background-color', fg).appendTo('body').css('background-color');
			bg = $('<span>').css('background-color', bg).appendTo('body').css('background-color');
			// Parse out numbers
			fg = fg.match(/\d+/g);
			bg = bg.match(/\d+/g);
			return Character(self, ch, fg, bg)
			
		};
		
		return self;
	};
	
	var Diagram = dfd.Diagram = function(opts){
		var self = {};
		opts = $.extend({
			'font': ''
		}, opts)
		
		self.init = function(){
			self.canvas = $('<canvas>')
		};
		
		return self;
	};
	window.dfd = dfd;
});
