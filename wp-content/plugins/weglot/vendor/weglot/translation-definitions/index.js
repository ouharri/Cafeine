// NOTE: This file should not contain any data itself.
var labelKey = "english_name"; // local_name | english_name

var wordType = require("./data/word-type.json");

var cases = {
  v1: require("./data/cases/cases-v1.json"),
  v2: require("./data/cases/cases-v2-js.json"),
  v3: require("./data/cases/cases-v3.json")
};

var languages = require("./data/available-languages.json")
  .sort(function(a, b) {
    return a[labelKey].localeCompare(b[labelKey]);
  })
  .map(function(language) {
    return {
      label: language[labelKey],
      value: language.code
    };
  });

module.exports = {
  cases: cases,
  mergeNodesList: require("./mergeNodesList").mergeNodesList,
  languages: languages,
  wordType: wordType
};
