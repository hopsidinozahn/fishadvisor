/*
 * Copyright © 2014 by Steve Muller. All rights reserved.
 * You may not copy, reproduce, alter, create derivates of, redistribute or sell this work without
 * explicit permission of the author. This copyright notice must not be removed.
 *
 */
// function lang(key) from main.js
function init_export(import_url, qr_code_url)
{
	var all_rods = { tg: lang("Twig"), bm: lang("Bamboo"), hw: lang("Hardwood"), st: lang("Steel"), gd: lang("Gold") };
	var max_fishes = { tg: 38, bm: 49, hw: 60, st: 69, gd: 70 };
	var all_fishes_order = [0,1,2,3,4,512,513,514,515,516,32,33,34,35,36,64,65,66,67,96,128,129,130,131,132,160,192,193,194,195,196,544,224,225,256,257,258,288,289,290,320,321,322,323,324,325,352,353,354,355,384,385,386,387,416,417,418,419,420,421,422,423,448,449,450,480,481,482,483,484];

	function import_control(ctrl)
	{
		var table = $("<table/>").addClass("importtable").appendTo(ctrl);
		var thead = $("<tr/>").appendTo($("<thead/>").appendTo(table));
		$("<th/>").appendTo(thead).text(lang("Toon to be imported"));
		$("<th/>").appendTo(thead).text(lang("Existing toon"));
		$("<th/>").appendTo(thead).text(lang("Actions"));
		var tbody = $("<tbody/>").appendTo(table);

		function render_toon(ctrl, toon)
		{
			$("<strong/>").appendTo(ctrl).text(toon.name);
			$("<em/>").appendTo(ctrl).text(all_rods[toon.rod] + ", " + lang("$/$ species").replace(/\$/, toon.fishes.length).replace(/\$/, max_fishes[toon.rod]));
		}
		function import_btn_callback()
		{
			$(this).closest("tr").remove();
			var t = $(this).data("toon");
			if (!t.id)
			{
				t.id = (new Date()).valueOf();
				t.name += lang(" (copy)");
			}
			else
			{
				t.id = parseInt(t.id);
			}

			var my_toons = localStorage.toons ? JSON.parse(localStorage.toons) : {};
			my_toons[t.id] = t;
			localStorage.toons = JSON.stringify(my_toons);
		}

		var my_toons = localStorage.toons ? JSON.parse(localStorage.toons) : {};
		var new_toons = read_import_data(atob(ctrl.data("import")));
		for (var i = 0; i < new_toons.length; i++)
		{
			var tr = $("<tr/>").appendTo(tbody);
			var td_old = $("<td/>").appendTo(tr);
			render_toon(td_old, new_toons[i]);
			var td_new = $("<td/>").appendTo(tr);
			var td_act = $("<td/>").appendTo(tr);
			if (new_toons[i].id in my_toons)
			{
				render_toon(td_new, my_toons[new_toons[i].id]);
				var clone = $.extend({}, new_toons[i], {id:0});
				$("<button/>").appendTo(td_act).text(lang("Replace")).attr("data-toon", JSON.stringify(new_toons[i])).click(import_btn_callback);
				$("<button/>").appendTo(td_act).text(lang("Create copy")).attr("data-toon", JSON.stringify(clone)).click(import_btn_callback);
			}
			else
			{
				td_new.text(lang("No matching toon found"));
				$("<button/>").appendTo(td_act).text(lang("Import")).attr("data-toon", JSON.stringify(new_toons[i])).click(import_btn_callback);
			}
		}
	}
	function read_import_data(data)
	{
		var toons = [];
		while (data.length > 0)
		{
			var i = data.indexOf("\t");
			var j = data.indexOf("\t", i + 1);
			if (i < 0 || j < 0) break;
			var t = {};
			t.id = data.substring(0, i);
			t.name = data.substring(i + 1, j);
			t.rod = data.substr(j + 1, 2);
			t.fishes = [];
			for (var i = 0; i < all_fishes_order.length; i++)
			{
				var has_it = data.charCodeAt(j+3 + (i-i%8) / 8) >> (i%8) & 1;
				if (has_it)
					t.fishes.push(all_fishes_order[i]);
			}
			toons.push(t);
			data = data.substring(j+4 + (all_fishes_order.length-1-(all_fishes_order.length-1)%8) / 8);
		}
		return toons;
	}
	function export_url_control(ctrl)
	{
		$("<input type=\"text\"/>").appendTo(ctrl).css({ "width": "600px", "font": "inherit" }).attr("readonly", "readonly");
		var ul = $("<ul/>").addClass("sharelist").appendTo(ctrl);
		$("<a/>").appendTo($("<li/>").appendTo(ul)).attr("id", "sendpermail").attr("href", "").text(lang("Send this link via e-mail"));
	}
	function export_qr_control(ctrl)
	{
	}
	function export_control(ctrl)
	{
		var ul = $("<ul/>").addClass("exporttoonlist").appendTo(ctrl);
		var toons = localStorage.toons ? JSON.parse(localStorage.toons) : {};
		function oncheck()
		{
			var data = "";
			ul.find("input:checked").each(function()
			{
				var id = $(this).attr("name");
				var t = toons[id];
				data += t.id + "\t" + t.name.replace(/\t/g, " ") + "\t" + t.rod;
				var fishval = 0;
				for (var i = 0; i < all_fishes_order.length; i++)
				{
					if (t.fishes.indexOf(all_fishes_order[i]) >= 0)
						fishval |= 1 << (i%8);
					if ((i + 1) % 8 == 0 || i + 1 == all_fishes_order.length)
					{
						data += String.fromCharCode(fishval);
						fishval = 0;
					}
				}
			});
			data = encodeURIComponent(btoa(data));
			$("#toons_export_url_control input").val(import_url + "?data=" + data);
			$("#toons_export_url_control #sendpermail").attr("href", "mailto:?subject=" + encodeURIComponent(lang("Fish Advisor: Import data")) + "&body=" + encodeURIComponent(import_url + "?data=" + data));
			$("#toons_export_qr_control").empty();
			$("<img/>").appendTo("#toons_export_qr_control").attr("src", qr_code_url + "?data=" + data).css("opacity", "0").load(function()
			{
				$(this).css("opacity", "1");
			});
		}
		for (var id in toons)
		{
			var li = $("<li/>").appendTo(ul);
			var label = $("<label/>").appendTo(li);
			$("<input type=\"checkbox\"/>").appendTo(label).change(oncheck).attr("name", id).attr("value", "1").attr("checked", "checked");
			$("<span/>").appendTo(label).text(toons[id].name);
		}
		oncheck();
	}
	$("#toons_export_url_control").each(function() { export_url_control($(this)); });
	$("#toons_export_qr_control").each(function() { export_qr_control($(this)); });
	$("#toons_export_control").each(function() { export_control($(this)); });
	$("#toons_import_control").each(function() { import_control($(this)); });
}