function changeTier(drop) {
  var tmpLoc = document.location + '';
  if (tmpLoc.indexOf('?') > 0) {
    document.location = tmpLoc + '&tier=' + drop.value;
  }
  else {
    document.location = tmpLoc + '?tier=' + drop.value;
  }
}
