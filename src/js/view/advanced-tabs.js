ea.hooks.addAction("init", "ea", () => {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-adv-tabs.default",
		function ($scope, $) {
			const $currentTab = $scope.find('.eael-advance-tabs'),
				$currentTabId = '#' + $currentTab.attr('id').toString()
			let hashTag = window.location.hash.substr(1);
			
			$($currentTabId + ' > .eael-tabs-nav ul li', $scope).each(function (index) {
				if (hashTag) {
					if ($(this).attr("id") == hashTag) {
						$(this).removeClass("inactive").addClass("active");
					}
				} else {
					if ($(this).hasClass("active-default")) {
						$($currentTabId + ' .eael-tabs-nav > ul li', $scope)
						.removeClass("active")
						.addClass("inactive");
						$(this).removeClass("inactive").addClass('active');
					} else {
						if (index == 0) {
							$(this).removeClass("inactive").addClass("active");
						}
					}
				}
			});
			
			$($currentTabId + ' > .eael-tabs-content > div', $scope).each(function (index) {
				if (hashTag) {
					if ($(this).attr("id") == hashTag) {
						$(this).removeClass("inactive").addClass("active");
					}
				} else {
					if ($(this).hasClass("active-default")) {
						$($currentTabId + ' > .eael-tabs-content > div', $scope).removeClass("active");
						$(this).removeClass("inactive").addClass("active");
					} else {
						if (index == 0) {
							$(this).removeClass("inactive").addClass("active");
						}
					}
				}
			});
			
			$($currentTabId + ' .eael-tabs-nav ul li', $scope).on("click", function (e) {
				e.preventDefault();
				
				var currentTabIndex = $(this).index();
				var tabsContainer = $(this).closest(".eael-advance-tabs");
				var tabsNav = $(tabsContainer)
				.children(".eael-tabs-nav")
				.children("ul")
				.children("li");
				var tabsContent = $(tabsContainer)
				.children(".eael-tabs-content")
				.children("div");
				
				$(this).parent("li").addClass("active");
				
				$(tabsNav).removeClass("active active-default").addClass("inactive");
				$(this).addClass("active").removeClass("inactive");
				
				$(tabsContent).removeClass("active").addClass("inactive");
				$(tabsContent)
				.eq(currentTabIndex)
				.addClass("active")
				.removeClass("inactive");
				
				$(tabsContent).each(function (index) {
					$(this).removeClass("active-default");
				});
				
				let $filterGallery = tabsContent
				.eq(currentTabIndex)
				.find(".eael-filter-gallery-container"),
					$postGridGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-post-grid.eael-post-appender"),
					$twitterfeedGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-twitter-feed-masonry"),
					$instaGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-instafeed"),
					$paGallery = tabsContent
					.eq(currentTabIndex)
					.find(".premium-gallery-container"),
					$evCalendar = $(".eael-event-calendar-cls", tabsContent);
				
				if ($postGridGallery.length) {
					$postGridGallery.isotope("layout");
				}
				
				if ($twitterfeedGallery.length) {
					$twitterfeedGallery.isotope("layout");
				}
				
				if ($filterGallery.length) {
					$filterGallery.isotope("layout");
				}
				
				if ($instaGallery.length) {
					$instaGallery.isotope("layout");
				}
				
				if ($paGallery.length) {
					$paGallery.each(function (index, item) {
						$(item).isotope("layout");
					});
				}
				
				if ($evCalendar.length) {
					ea.hooks.doAction("eventCalendar.reinit");
				}
			});
		}
	);
});
