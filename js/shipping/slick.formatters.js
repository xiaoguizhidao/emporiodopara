/***
 * Contains basic SlickGrid formatters.
 * @module Formatters
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Formatters": {
        "PercentComplete": PercentCompleteFormatter,
        "PercentCompleteBar": PercentCompleteBarFormatter,
        "YesNo": YesNoFormatter,
        "Checkmark": CheckmarkFormatter,
        'Decimal' : DecimalFormatter
      }
    }
  });


  function DecimalFormatter(row, cell, value, columnDef, dataContext){


      if(value != undefined){
          value = value.replace(',' , '.');
          if (value == null || value === "" || isNaN(value) ) {
              //data[row][array_data[cell]] = null; 
              return null;
          }else{
            data[row]["'"+columnDef.field+"'"] = parseFloat(Math.round(value * 100) / 100).toFixed(2);
            return parseFloat(Math.round(value * 100) / 100).toFixed(2);
          }
      }else{
          //data[row][array_data[cell]] = null; 
          return null;
      }
  }

  function PercentCompleteFormatter(row, cell, value, columnDef, dataContext) {
    if (value == null || value === "") {
      return "-";
    } else if (value < 50) {
      return "<span style='color:red;font-weight:bold;'>" + value + "%</span>";
    } else {
      return "<span style='color:green'>" + value + "%</span>";
    }
  }

  function PercentCompleteBarFormatter(row, cell, value, columnDef, dataContext) {
    if (value == null || value === "") {
      return "";
    }

    var color;

    if (value < 30) {
      color = "red";
    } else if (value < 70) {
      color = "silver";
    } else {
      color = "green";
    }

    return "<span class='percent-complete-bar' style='background:" + color + ";width:" + value + "%'></span>";
  }

  function YesNoFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "Yes" : "No";
  }

  function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "<img src='../images/tick.png'>" : "";
  }
})(jQuery);