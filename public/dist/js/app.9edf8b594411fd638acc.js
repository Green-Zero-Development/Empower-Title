!function(e){var t={};function o(n){if(t[n])return t[n].exports;var r=t[n]={i:n,l:!1,exports:{}};return e[n].call(r.exports,r,r.exports,o),r.l=!0,r.exports}o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)o.d(n,r,function(t){return e[t]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=0)}([function(e,t,o){"use strict";var n,r=o(1);(n=r)&&n.__esModule;document.addEventListener("DOMContentLoaded",(function(){var e;if("IntersectionObserver"in window){e=document.querySelectorAll(".laz-img");var t=new IntersectionObserver((function(e,o){e.forEach((function(e){if(e.isIntersecting){var o=e.target;o.classList.remove("laz-img"),t.unobserve(o)}}))}));e.forEach((function(e){t.observe(e)}))}else{var o,n=function t(){o&&clearTimeout(o),o=setTimeout((function(){var o=window.pageYOffset;e.forEach((function(e){e.offsetTop<window.innerHeight+o&&(e.src=e.dataset.src,e.classList.remove("lazy"))})),0==e.length&&(document.removeEventListener("scroll",t),window.removeEventListener("resize",t),window.removeEventListener("orientationChange",t))}),20)};e=document.querySelectorAll(".laz-img"),document.addEventListener("scroll",n),window.addEventListener("resize",n),window.addEventListener("orientationChange",n)}}));var c=document.querySelector(".mobile-menu-button"),i=document.querySelector(".mobile-menu"),l=document.querySelector("body");c.onclick=function(){i.classList.toggle("mobile-menu-toggle"),l.classList.toggle("overflow-hidden")},document.querySelector(".close-menu").onclick=(i.classList.toggle("mobile-menu-toggle"),void l.classList.toggle("overflow-hidden"))();var u=document.querySelector(".read-more"),s=document.querySelector(".read-more-content"),a=document.querySelector(".read-more-carot");u.onclick=function(){u.classList.toggle("text-color-de320a"),s.classList.toggle("text-lg"),a.classList.toggle("fa-caret-up")};var d=document.querySelector(".read-more2"),f=document.querySelector(".read-more-content2"),m=document.querySelector(".read-more-carot2");d.onclick=function(){d.classList.toggle("text-color-de320a"),f.classList.toggle("text-lg"),m.classList.toggle("fa-caret-up")}},function(e,t,o){}]);