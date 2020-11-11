/*
 * Copyright © 2014 by Steve Muller. All rights reserved.
 * You may not copy, reproduce, alter, create derivates of, redistribute or sell this work without
 * explicit permission of the author. This copyright notice must not be removed.
 *
 */
function lang(key)
{
	if (window.languagedict && key in window.languagedict)
		return window.languagedict[key];
	else
		return key;
}
function init(web_root, selected_rod_id)
{
	var all_rods = { tg: lang("Twig"), bm: lang("Bamboo"), hw: lang("Hardwood"), st: lang("Steel"), gd: lang("Gold") };
	var last_rod = "gd";
	var max_fishes = { tg: 38, bm: 49, hw: 60, st: 69, gd: 70 };
	var all_fishes = {0:lang("Balloon Fish"),1:lang("Hot Air Balloon Fish"),2:lang("Weather Balloon Fish"),3:lang("Water Balloon Fish"),4:lang("Red Balloon Fish"),32:lang("Cat Fish"),33:lang("Siamese Cat Fish"),34:lang("Alley Cat Fish"),35:lang("Tabby Cat Fish"),36:lang("Tom Cat Fish"),64:lang("Clown Fish"),65:lang("Sad Clown Fish"),66:lang("Party Clown Fish"),67:lang("Circus Clown Fish"),96:lang("Frozen Fish"),128:lang("Star Fish"),129:lang("Five Star Fish"),130:lang("Rock Star Fish"),131:lang("Shining Star Fish"),132:lang("All Star Fish"),160:lang("Holey Mackerel"),192:lang("Dog Fish"),193:lang("Bull Dog Fish"),194:lang("Hot Dog Fish"),195:lang("Dalmatian Dog Fish"),196:lang("Puppy Dog Fish"),224:lang("Amore Eel"),225:lang("Electric Amore Eel"),256:lang("Nurse Shark"),257:lang("Clara Nurse Shark"),258:lang("Florence Nurse Shark"),288:lang("King Crab"),289:lang("Alaskan King Crab"),290:lang("Old King Crab"),320:lang("Moon Fish"),321:lang("Full Moon Fish"),322:lang("Half Moon Fish"),323:lang("New Moon Fish"),324:lang("Crescent Moon Fish"),325:lang("Harvest Moon Fish"),352:lang("Sea Horse"),353:lang("Rocking Sea Horse"),354:lang("Clydesdale Sea Horse"),355:lang("Arabian Sea Horse"),384:lang("Pool Shark"),385:lang("Kiddie Pool Shark"),386:lang("Swimming Pool Shark"),387:lang("Olympic Pool Shark"),416:lang("Brown Bear Acuda"),417:lang("Black Bear Acuda"),418:lang("Koala Bear Acuda"),419:lang("Honey Bear Acuda"),420:lang("Polar Bear Acuda"),421:lang("Panda Bear Acuda"),422:lang("Kodiac Bear Acuda"),423:lang("Grizzly Bear Acuda"),448:lang("Cutthroat Trout"),449:lang("Captain Cutthroat Trout"),450:lang("Scurvy Cutthroat Trout"),480:lang("Piano Tuna"),481:lang("Grand Piano Tuna"),482:lang("Baby Grand Piano Tuna"),483:lang("Upright Piano Tuna"),484:lang("Player Piano Tuna"),512:lang("Peanut Butter & Jellyfish"),513:lang("Grape PB&J Fish"),514:lang("Crunchy PB&J Fish"),515:lang("Strawberry PB&J Fish"),516:lang("Concord Grape PB&J Fish"),544:lang("Devil Ray")};
	var all_fishes_order = [0,1,2,3,4,512,513,514,515,516,32,33,34,35,36,64,65,66,67,96,128,129,130,131,132,160,192,193,194,195,196,544,224,225,256,257,258,288,289,290,320,321,322,323,324,325,352,353,354,355,384,385,386,387,416,417,418,419,420,421,422,423,448,449,450,480,481,482,483,484];
	var rods_by_fish = {0:["tg","bm","hw","st","gd"],1:["tg","bm","hw","st","gd"],2:["tg","bm","hw","st","gd"],3:["tg","bm","hw","st","gd"],4:["tg","bm","hw","st","gd"],512:["tg","bm","hw","st","gd"],513:["tg","bm","hw","st","gd"],514:["tg","bm","hw","st","gd"],515:["tg","bm","hw","st","gd"],516:["tg","bm","hw","st","gd"],32:["tg","bm","hw","st","gd"],33:["tg","bm","hw","st","gd"],34:["bm","hw","st","gd"],35:["tg","bm","hw","st","gd"],36:["bm","hw","st","gd"],64:["tg","bm","hw","st","gd"],65:["tg","bm","hw","st","gd"],66:["tg","bm","hw","st","gd"],67:["tg","bm","hw","st","gd"],96:["bm","hw","st","gd"],128:["tg","bm","hw","st","gd"],129:["tg","bm","hw","st","gd"],130:["bm","hw","st","gd"],131:["tg","bm","hw","st","gd"],132:["tg","bm","hw","st","gd"],160:["bm","hw","st","gd"],192:["bm","hw","st","gd"],193:["gd"],194:["tg","bm","hw","st","gd"],195:["tg","bm","hw","st","gd"],196:["tg","bm","hw","st","gd"],544:["tg","bm","hw","st","gd"],224:["tg","bm","hw","st","gd"],225:["tg","bm","hw","st","gd"],256:["tg","bm","hw","st","gd"],257:["tg","bm","hw","st","gd"],258:["tg","bm","hw","st","gd"],288:["tg","bm","hw","st","gd"],289:["bm","hw","st","gd"],290:["tg","bm","hw","st","gd"],320:["tg","bm","hw","st","gd"],321:["st","gd"],322:["bm","hw","st","gd"],323:["tg","bm","hw","st","gd"],324:["tg","bm","hw","st","gd"],325:["hw","st","gd"],352:["hw","st","gd"],353:["st","gd"],354:["st","gd"],355:["st","gd"],384:["hw","st","gd"],385:["bm","hw","st","gd"],386:["bm","hw","st","gd"],387:["bm","hw","st","gd"],416:["hw","st","gd"],417:["hw","st","gd"],418:["hw","st","gd"],419:["hw","st","gd"],420:["hw","st","gd"],421:["st","gd"],422:["st","gd"],423:["st","gd"],448:["tg","bm","hw","st","gd"],449:["tg","bm","hw","st","gd"],450:["tg","bm","hw","st","gd"],480:["st","gd"],481:["st","gd"],482:["hw","st","gd"],483:["hw","st","gd"],484:["hw","st","gd"]};
	var all_ponds = {"tc":lang("Toontown Central"),"tc_pp":lang("Punchline Place"),"tc_ss":lang("Silly Street"),"tc_ll":lang("Loopy Lane"),"dd":lang("Donald's Dock"),"dd_bb":lang("Barnacle Boulevard"),"dd_ll":lang("Lighthouse Lane"),"dd_ss":lang("Seaweed Street"),"dg":lang("Daisy Gardens"),"dg_es":lang("Elm Street"),"dg_ms":lang("Maple Street"),"dg_os":lang("Oak Street"),"mm":lang("Minnie's Melodyland"),"mm_aa":lang("Alto Avenue"),"mm_bb":lang("Baritone Boulevard"),"mm_tt":lang("Tenor Terrace"),"tb":lang("The Brrrgh"),"tb_ss":lang("Sleet Street"),"tb_ww":lang("Walrus Way"),"tb_pp":lang("Polar Place"),"dl":lang("Donald's Dreamland"),"dl_ll":lang("Lullaby Lane"),"dl_pp":lang("Pajama Place"),"ee":lang("Estate")};

	var text_caught = lang("has caught this fish species ");
	var text_notcaught = lang("has not caught this fish species yet ");
	var text_undo = lang("(undo)");
	var text_catch = lang("(catch now)");
	var text_catch_button = lang("Catch now!");

	function toggleFish(toonId, fishId, forced_value)
	{
		var toons = localStorage.toons ? JSON.parse(localStorage.toons) : {};
		var toonId = parseInt(localStorage.currentToonId);
		var i = toons[toonId].fishes.indexOf(fishId);
		var has_fish_now;
		if (i >= 0 && (typeof forced_value == "undefined" || !forced_value))
		{
			toons[toonId].fishes.splice(i, 1);
			has_fish_now = false;
		}
		else if (i < 0 && (typeof forced_value == "undefined" || forced_value))
		{
			toons[toonId].fishes.push(fishId);
			has_fish_now = true;
		}
		localStorage.toons = JSON.stringify(toons);
		return has_fish_now;
	}

	function StatsDataControl(container, mode)
	{
		container = $(container);
		var render = function(rod_id) { return function(data)
		{
			container.empty();
			var table = $("<tbody/>").appendTo($("<table/>").addClass(mode + "list pondorfishlist").appendTo(container));
			for (var i = 0; i < data.length; i++)
			{
				var e = data[i]; // get entry
				var tr = $("<tr/>").appendTo(table);
				if (mode == "pond")
					$("<td/>").appendTo(tr).attr("colspan", "3").addClass("icon pond_" + e.id);
				else
					$("<td/>").appendTo(tr).attr("colspan", "3").addClass("icon fishgroup_" + e.group);

				var td_name = $("<td/>").appendTo(tr).addClass("name");
				var url = web_root + (mode == "pond" ? "ponds/" : "fishes/") + e.id + ".html?rid=" + rod_id
				$("<strong/>").appendTo(td_name).append($("<a/>").attr("href", url).text(lang(e.name)));
				if (e.samples)
				{
					var td_name_samples = $("<small/>").appendTo(td_name);
					for (var j = 0; j < e.samples.length; j++)
					{
						var s = e.samples[j];
						var url = web_root + (mode == "pond" ? "fishes/" : "ponds/") + s.id + ".html?rid=" + rod_id
						$("<a/>").appendTo(td_name_samples).attr("href", url).text(lang(s.name) + " (" + s.prob + ")");
					}
					if (e.num_total_samples > e.samples.length)
						td_name_samples.append(document.createTextNode(lang(" ... and $ more").replace(/\$/g, e.num_total_samples - e.samples.length)));
				}

				var td_prob = $("<td/>").appendTo(tr).addClass("probability");
				$("<strong/>").appendTo(td_prob).text(e.prob);
				$("<small/>").appendTo(td_prob).text(e.num_buckets <= 1 ? lang("needs 1 bucket") : lang("needs $ buckets").replace(/\$/g, e.num_buckets));
			}
			if (data.length == 0)
			{
				var tr = $("<tr/>").appendTo(table);
				var td = $("<td/>").appendTo(tr).css("text-align", "center").attr("colspan", "3");
				if (mode == "pond" || rod_id == last_rod)
					td.text(lang("You have caught all possible fish species with this rod."));
				else
				{
					$("<p/>").appendTo(td).text(lang("You have caught all fish species that are more common with $ than with later rods.").replace(/\$/g, all_rods[rod_id]));
					$("<p/>").appendTo(td).text(lang("You can safely buy a new rod from your cattlelog now."));
				}
			}
		}; };
		var onError = function(jqxhr, textStatus, error)
		{
			container.text(lang("Failed to retrieve data: ") + textStatus + ", " + error);
		};
		this.fetchAndRender = function(rod_id, caught_species)
		{
			$.getJSON(web_root + mode + "_advisor.json", { rid: rod_id, fid: caught_species }).done(render(rod_id)).fail(onError);
		};
	}
	function ToonsControl()
	{
		var self = this;
		this.events = {};

		this.createToonPanel = function(toon)
		{
			var li = $("<li/>").attr("id", "toon_" + toon.id);
			if (toon.id == localStorage.currentToonId)
			{
				li.addClass("current");
			}
			$("<p/>").appendTo(li).addClass("name").text(toon.name);
			$("<p/>").appendTo(li).addClass("rod").text(all_rods[toon.rod]);
			$("<p/>").appendTo(li).addClass("fishes").text(lang("$/$ species").replace(/\$/, toon.fishes.length).replace(/\$/, max_fishes[toon.rod]));
			li.click(function()
			{
				localStorage.currentToonId = toon.id;
				$(this).siblings("li").removeClass("current");
				$(this).addClass("current");

				$(self.events).trigger("onToonSelected", [toon]);
				$(self.events).trigger("onToonSelectionChanged", [toon]);
			});
			return li;
		}

		this.createUnselectPanel = function()
		{
			return $("<li/>").addClass("none").text("✘").click(function()
			{
				localStorage.removeItem("currentToonId");
				$(this).siblings("li").removeClass("current");
				$(this).addClass("current");

				$(self.events).trigger("onToonUnselected");
				$(self.events).trigger("onToonSelectionChanged", [null]);
			});
		}

		this.createAddPanel = function(panelClickHandler)
		{
			return $("<li/>").addClass("add").text("+").click(function()
			{
				var name = prompt(lang("Enter the name of the toon you wish to create:"));
				if (name)
				{
					var newtoon = { id: (new Date()).valueOf(), name: name, rod: "tg", fishes: [] };
					var toons = self.getToons();
					toons[newtoon.id] = newtoon;
					localStorage.toons = JSON.stringify(toons);
					var panel = self.createToonPanel(newtoon).insertBefore(this);
					if (panelClickHandler)
					{
						panel.click(function() { panelClickHandler.call(this, newtoon); }).click();
					}
				}
			});
		}

		this.getToons = function()
		{
			return localStorage.toons ? JSON.parse(localStorage.toons) : {};
		}

		this.render = function(container)
		{
			var ul = $("<ul/>").addClass("toonlist").appendTo(container);
			var unselect_panel = self.createUnselectPanel().appendTo(ul);
			var toons = self.getToons();
			for (var id in toons)
				self.createToonPanel(toons[id]).appendTo(ul);
			if (ul.find(".current").length == 0)
				unselect_panel.addClass("current");
			return ul;
		};
	}
	function ToonsMiniControl()
	{
		var self = this;
		this.render = function(container)
		{
			var ul = $("<ul/>").addClass("minitoonlist").appendTo(container);
			var unselect_panel = self.createUnselectPanel().appendTo(ul);
			var toons = self.getToons();
			for (var id in toons)
				self.createToonPanel(toons[id]).appendTo(ul).text(toons[id].name);
			if (ul.find(".current").length == 0)
				unselect_panel.addClass("current");
			return ul;
		};
	}
	ToonsMiniControl.prototype = new ToonsControl();
	ToonsMiniControl.prototype.constructor = ToonsMiniControl;
	function ToonsMgmtControl()
	{
		var self = this;
		this.render = function(container)
		{
			container = $(container);
			var panelClickHandler = function(toon)
			{
				container.find("form").remove();
				var edit = $("<form/>").submit(function(){return false;}).appendTo(container);
				var h2 = $("<h2/>").appendTo(edit).text(lang("Edit selected toon"));
				var p1 = $("<p/>").appendTo(edit).text(lang("Toon's name")).append("<br/>");
				var fld_name = $("<input type=\"text\"/>").appendTo(p1).css({width: "400px", marginTop: "5px"}).val(toon.name);
				var p2 = $("<p/>").appendTo(edit).text(lang("Rod")).append("<br/>");
				var fld_rod = $("<select size=\"1\"/>").appendTo(p2).css({width: "400px", marginTop: "5px"});
				for (var rid in all_rods)
					$("<option/>").appendTo(fld_rod).attr("value", rid).text(all_rods[rid]);
				fld_rod.val(toon.rod);
				var p3 = $("<p/>").appendTo(edit).text(lang("Caught fish species")).append("<br/>");
				var fld_fishlist = $("<ul/>").appendTo(p3).addClass("minifishlist");
				for (var i = 0; i < all_fishes_order.length; i++)
				{
					var fishid = all_fishes_order[i];
					var li = $("<li/>").appendTo(fld_fishlist).attr("x-fishid", fishid);
					var lbl = $("<label/>").appendTo(li).addClass("fishgroup_" + (fishid >> 4));
					var cb = $("<input type=\"checkbox\"/>").appendTo(lbl).attr("x-fishid", fishid).attr("value", "1");
					for (var j = 0; j < toon.fishes.length; j++)
						if (toon.fishes[j] == fishid)
						{
							cb.attr("checked", "checked");
							break;
						}
					lbl.append(document.createTextNode(" " + all_fishes[fishid]));
				}
				function save()
				{
					$(this).stop(true, true).
						animate({ opacity: "0" }, 150).
						animate({ opacity: "1" }, 150).
						animate({ opacity: "0" }, 150).
						animate({ opacity: "1" }, 150);

					// Store new name
					toon.name = fld_name.val();
					toon.rod = fld_rod.val();
					toon.fishes = [];
					fld_fishlist.find("input:checked").each(function()
					{
						toon.fishes.push(parseInt($(this).attr("x-fishid")));
					});
					var toons = self.getToons();
					if (toon.id in toons)
						toons[toon.id] = toon;
					localStorage.toons = JSON.stringify(toons);

					// Update GUI
					$("#toon_" + toon.id + ">.name").text(toon.name);
					$("#toon_" + toon.id + ">.rod").text(all_rods[toon.rod]);
					$("#toon_" + toon.id + ">.fishes").text(toon.fishes.length + "/" + max_fishes[toon.rod] + " species");
				}
				fld_rod.change(function()
				{
					$(".minifishlist li").removeClass("cantcatch");
					var new_selected_rod = $(this).val();
					for (var fid in rods_by_fish)
						if (rods_by_fish[fid].indexOf(new_selected_rod) < 0)
							$(".minifishlist li[x-fishid=" + fid + "]").addClass("cantcatch");
				}).change();
				fld_name.change(save);
				fld_rod.change(save);
				fld_fishlist.find("input").change(save);
				var p4 = $("<p/>").css({ margin: "0 -50px", padding: "20px 50px", background: "#222" }).insertAfter(h2);
				$("<button type=\"button\"/>").appendTo(p4).text(lang("Delete toon")).click(function()
				{
					if (confirm(lang("Really delete $?").replace(/\$/g, toon.name)))
					{
						// Remove from storage
						var toons = self.getToons();
						if (toon.id in toons)
							delete toons[toon.id];
						localStorage.toons = JSON.stringify(toons);

						// Update GUI
						$("#toon_" + toon.id).remove();
						container.find(".none").addClass("current");
						edit.remove();
					}
				});
			};
			var ul = $("<ul/>").addClass("toonlist").appendTo(container);
			var unselect_panel = self.createUnselectPanel().appendTo(ul).click(function()
			{
				container.find("form").remove();
			});
			var toons = self.getToons();
			for (var id in toons)
			{
				self.createToonPanel(toons[id]).appendTo(ul).click(function(toon){return function(){panelClickHandler(toon);};}(toons[id]));
			}
			self.createAddPanel(panelClickHandler).appendTo(ul);
			if (ul.find(".current").length == 0)
				unselect_panel.addClass("current");
			return ul;
		};
	}
	ToonsMgmtControl.prototype = new ToonsControl();
	ToonsMgmtControl.prototype.constructor = ToonsMgmtControl;
	function loadAdvisorControl(container, mode)
	{
		container = $(container).empty();
		var toons = typeof(Storage) !== "undefined" && localStorage.toons ? JSON.parse(localStorage.toons) : {};
		if (Object.keys(toons).length > 0)
		{
			var toonId = parseInt(localStorage.currentToonId);
			if (!isNaN(toonId) && toonId in toons)
			{
				$("<p/>").appendTo(container).addClass("status").text(lang("Doing some awesome mathematical computations ..."));
				(new StatsDataControl(container, mode)).fetchAndRender(toons[toonId].rod, toons[toonId].fishes);
			}
			else
			{
				$("<p/>").appendTo(container).css("margin", "30px 110px").text(lang("Please pick a toon which should be advised."));
				var tctrl = new ToonsControl();
				$(tctrl.events).on("onToonSelected", updateOnSelectedToon);
				tctrl.render(container).css("margin", "0 100px");
			}
		}
		else
		{
			var p = $("<p/>").addClass("status").appendTo(container);
			p.append(document.createTextNode(lang("You have not created any toons yet. Head over to the ")));
			$("<a/>").appendTo(p).attr("href", web_root + "toons.html").text(lang("toon management panel"));
			p.append(document.createTextNode(lang(" and create a toon there before returning to the Advisor.")));
		}
	}
	function loadToonsMgmtControl(container)
	{
		if (typeof(Storage) === "undefined")
		{
			$("<p/>").appendTo(container).addClass("error").text(lang("We're sorry but your web browser does not support the necessary HTML5 features. Please upgrade your browser."));
			return;
		}
		(new ToonsMgmtControl()).render(container);
	}
	function loadToonsControl(container)
	{
		var toons = typeof(Storage) !== "undefined" && localStorage.toons ? JSON.parse(localStorage.toons) : {};
		if (Object.keys(toons).length > 0)
		{
			var tctrl = new ToonsControl();
			$(tctrl.events).on("onToonSelectionChanged", function(e, toon) { updateOnSelectedToon(); });
			tctrl.render(container);
		}
		else
		{
			var p = $("<p/>").addClass("status").appendTo(container);
			p.append(document.createTextNode(lang("You have not created any toons yet. Head over to the ")));
			$("<a/>").appendTo(p).attr("href", web_root + "toons.html").text(lang("toon management panel"));
			p.append(document.createTextNode(lang(" and create a toon there.")));
		}
	}
	function updateOnSelectedToon()
	{
		if (typeof(Storage) === "undefined") return;

		var toons = localStorage.toons ? JSON.parse(localStorage.toons) : {};
		var toonId = parseInt(localStorage.currentToonId);

		$("table.pondorfishlist").each(function()
		{
			if ($(this).find("tbody:hidden").length == 0)
			{
				$(this).find("tbody").clone().hide().appendTo(this);
			}
			else
			{
				$(this).find("tbody:visible").remove();
				$(this).find("tbody").clone().show().prependTo(this);
			}
		});

		$("ul.fishgroup>li").removeClass("caught cantcatch");
		$(".minitoonlist>li").removeClass("current");

		if (Object.keys(toons).length == 0)
			$("#toon_fish_status").parent().remove();

		if (Object.keys(toons).length > 0 && !isNaN(toonId) && toonId in toons)
		{
			$("#toon_" + toonId).addClass("current");
			var rod = toons[toonId].rod;
			var fishes = toons[toonId].fishes;
			for (var i = 0; i < fishes.length; i++)
			{
				$("ul.fishgroup>li.fish_" + fishes[i]).addClass("caught");
				$("table.fishlist>tbody:visible>#fish_" + fishes[i]).remove();
			}
			$("ul.fishgroup>li.cantcatch_" + rod).addClass("cantcatch");

			$("#toon_fish_status").each(function()
			{
				var node_fish_status = $("<span/>").appendTo($(this).empty());
				var current_fish_id = $(this).data("fishid");
				var catch_button = $("<a/>").addClass("catchbutton").attr("href", "javascript:;");

				if (fishes.indexOf(current_fish_id) >= 0)
				{
					$(node_fish_status).text(text_caught);
					catch_button.text(text_undo);
				}
				else
				{
					$(node_fish_status).text(text_notcaught);
					catch_button.text(text_catch);
				}
				catch_button.appendTo(this).click(function()
				{
					if (toggleFish(toonId, current_fish_id))
					{
						toons[toonId].fishes.push(current_fish_id);
						$(node_fish_status).text(text_caught);
						$(this).text(text_undo);
					}
					else
					{
						$(node_fish_status).text(text_notcaught);
						$(this).text(text_catch);
					}
				});
			});

			$(".fishlist tr:visible").each(function()
			{
				var node_row = this;
				var current_fish_id = $(this).data("id");
				$("<button/>").prependTo($(this).find(".name")).addClass("catchbutton").text(text_catch_button).click(function()
				{
					toggleFish(toonId, current_fish_id, true);
					var tr = $("<tr/>").insertAfter(node_row).css("height", "40px");
					var td = $("<td/>").appendTo(tr).attr("colspan", "3").css("text-align", "center").text(lang("Caught $.").replace(/\$/g, all_fishes[current_fish_id]) + " ");
					$("<a/>").appendTo(td).attr("href", "javascript:;").text(lang("Undo?")).click(function()
					{
						$(node_row).insertBefore(tr);
						tr.remove();
						toggleFish(toonId, current_fish_id, false);
					});
					$(node_row).detach();
				});
			});

			if (rod != selected_rod_id)
			{
				$("table.pondorfishlist>tbody:visible").each(function()
				{
					$(this).find("tr").hide();
					var tr = $("<tr/>").prependTo(this);
					var td = $("<td/>").appendTo(tr).attr("colspan", "3").css({ height: "300px", textAlign: "center", lineHeight: "150%" });
					td.append(document.createTextNode(lang("You have selected to display statistics for the ")));
					$("<b/>").appendTo(td).text(all_rods[selected_rod_id]);
					td.append(document.createTextNode(lang(" rod, but your toon owns the ")));
					$("<b/>").appendTo(td).text(all_rods[rod]);
					td.append(document.createTextNode(lang(" rod.")));
					td.append("<br/>");
					td.append(document.createTextNode(lang("The resulting probabilities will not be correct for your toon.")));

					var p = $("<p/>").appendTo(td);
					$("<button/>").appendTo(p).addClass("rodbutton rod_" + selected_rod_id).text(lang("Stick with $ nevertheless").replace(/\$/g, all_rods[selected_rod_id])).click(function()
					{
						tr.remove();
						$("table.pondorfishlist>tbody:visible>tr").show();
					});
					p.append(" ");
					$("<button/>").appendTo(p).addClass("rodbutton toonrodbutton rod_" + rod).text(lang("Show me the $ statistics for my toon").replace(/\$/g, all_rods[rod])).click(function()
					{
						td.text(lang("Getting you there ..."));
						location.href = "?rid=" + rod;
					});
				});
			}
		}
		else
		{
			$(".minitoonlist>.none").addClass("current");
			$("#toon_fish_status").text(lang("← select a toon"));
		}

		$("#pond_advisor_control").each(function() { loadAdvisorControl(this, "pond"); });
		$("#species_advisor_control").each(function() { loadAdvisorControl(this, "fish"); });
	}
	function setupBucketInfoModalWindow(element)
	{
		element = $(element);
		var table = element.closest(".pondorfishlist");
		var fid, pid;
		if (table.is(".pondlist"))
		{
			fid = table.data("fid");
			pid = element.closest("tr").data("id");
		}
		else
		{
			fid = element.closest("tr").data("id");
			pid = table.data("pid");
		}
		var text = element.text() + " (?)";
		$("<a/>").appendTo(element.empty()).attr("href", "javascript:;").text(text).click(function()
		{
			var modal = showModal().text(lang("Doing some awesome mathematical computations ..."));
			$.getJSON(web_root + "buckets.json", { rid: selected_rod_id, fid: fid, pid: pid }).done(function(data)
			{
				modal.empty();
				$("<h3/>").appendTo(modal).text(lang("What does this number mean?")).css("margin", 0);
				$("<p/>").appendTo(modal).text(
					lang("It states that you will most likely need to fish at most $N buckets at the pond in $P to catch the $F.").
					replace(/\$N/g, data[selected_rod_id].buckets).
					replace(/\$F/g, all_fishes[fid]).
					replace(/\$P/g, all_ponds[pid]));
				$("<p/>").appendTo(modal).text(lang("These do not have to be fished continously, so you are allowed to leave the pond and fish somewhere else or quit the game."));
				var p = $("<p/>").appendTo(modal).
					addClass("important").attr("x-lang-important", lang("Important!")).
					text(lang("Only buckets caught at *this pond* with *this rod* can be included."));
				if (Object.keys(data).length > 1)
				{
					p.append(document.createTextNode(" " + lang("Buckets fished with previous rods can be converted as follows.")));
					var table = $("<table/>").appendTo(modal).addClass("datatable");
					var tr = $("<tr/>").appendTo($("<thead/>").appendTo(table));
					$("<th/>").appendTo(tr).text(lang("1 bucket with rod ..."));
					$("<th/>").appendTo(tr).text(lang("corresponds to ... with $.").replace(/\$/g, all_rods[selected_rod_id]));
					var tbody = $("<tbody/>").appendTo(table);
					for (var rod_id in data)
					{
						if (rod_id == selected_rod_id) continue;
						var tr = $("<tr/>").appendTo(tbody);
						$("<td/>").appendTo(tr).text(all_rods[rod_id]);
						$("<td/>").appendTo(tr).text(lang("$ buckets").replace(/\$/g, data[rod_id].bucket_factor));
					}
					$("<p/>").appendTo(modal).
						addClass("important").attr("x-lang-important", lang("Important!")).
						text(lang("These conversion rules only apply to this species at this pond!"));
				}
			}).fail(function(jqxhr, textStatus, error)
			{
				modal.text(lang("Failed to retrieve data: ") + textStatus + ", " + error);
			});
		});
	}
	function showModal()
	{
		var bg = $("<div/>").appendTo("body").hide().addClass("modal-bg");
		var modal = $("<div/>").appendTo(bg).addClass("modal");
		function doRemove()
		{
			modal.animate({marginTop: "+=30px"}, 300);
			bg.fadeOut(300, function() { $(this).remove(); });
		}
		$("<button/>").appendTo(modal).addClass("close").click(doRemove);
		bg.click(doRemove).fadeIn(300);
		modal.animate({marginTop: "+=30px"}, 300);
		return $("<div/>").appendTo(modal);
	}

	// function init() body

	// I18n
	$("ul.fishgroup>li").attr("x-lang-caught", lang("caught")).attr("x-lang-cantcatch", lang("can't catch"));
	$(".rodlist").attr("x-lang-change", lang("(change)"));

	// E-Mail address obfuscation
	$(".omx").each(function()
	{
		var mx = $(this).text().replace(/DOT/g, '.').replace(/AT/g, '@');
		$(this).attr("href", "mailto:" + mx).text(mx);
	});

	updateOnSelectedToon();
	$("#toon_selection").each(function()
	{
		var tctrl = new ToonsMiniControl();
		$(tctrl.events).on("onToonSelectionChanged", function(e, toon) { updateOnSelectedToon(); });
		tctrl.render(this);
	});
	$("#toons_mgmt_control").each(function() { loadToonsMgmtControl(this); });
	$("#toons_control").each(function() { loadToonsControl(this); });
	$(".pondorfishlist .probability small").each(function() { setupBucketInfoModalWindow(this); });

	// Some mobile devices have problems with the :hover attribute. Simulate a similar behaviour.
	$(".rodlist").click(function(e) { $(this).toggleClass("hover"); e.stopPropagation(); });
	$(document).click(function() { $(".rodlist").removeClass("hover"); });
}