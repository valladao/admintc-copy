"use strict"
/**
 * Description
 *  	 allFilters Filster
 */

// From financial page
// To change price to brazilian format
app.filter("dot2comma", function () {
  return function (item) {
    return item.toString().replace(".", ",")
  }
})

app.filter("properCase", function () {
  return function (input) {
    return !!input
      ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase()
      : ""
  }
})

// From fiscal page
// To prepare variant description to show in ECF
app.filter("notModel", function () {
  return function (item) {
    if (item == "Modelo") {
    } else {
      return " - " + item.toUpperCase()
    }
  }
})

// To prepare variant description to show in ECF
app.filter("notStandard", function () {
  return function (item) {
    if (item == "Padrão") {
    } else {
      return " - " + item.toUpperCase()
    }
  }
})

// To assign number to origin field
app.filter("originNum", function () {
  return function (item) {
    switch (item) {
      case "ESTRANGEIRA - ADQUIRIDA NO MERCADO INTERNO":
        return 2
        break
      case "ESTRANGEIRA - IMPORTAÇÃO DIRETA":
        return 1
        break
      case "NACIONAL":
        return 0
        break
    }
  }
})

// To prepare registerTime information
app.filter("onlyTime", function () {
  return function (item) {
    return item.toString().slice(11)
  }
})

// Pay attention that for JS, month starts at 0
app.filter("translateMonth", function () {
  return function (item) {
    const months = [
      "Janeiro",
      "Fevereiro",
      "Março",
      "Abril",
      "Maio",
      "Junho",
      "Julho",
      "Agosto",
      "Setembro",
      "Outubro",
      "Novembro",
      "Dezembro",
    ]
    return months[item]
  }
})

// Directly number to month translation
app.filter("translateMonth2", function () {
  return function (item) {
    const months = [
      "",
      "Janeiro",
      "Fevereiro",
      "Março",
      "Abril",
      "Maio",
      "Junho",
      "Julho",
      "Agosto",
      "Setembro",
      "Outubro",
      "Novembro",
      "Dezembro",
    ]
    return months[item]
  }
})
