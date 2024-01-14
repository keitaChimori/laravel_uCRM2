// テキストエリアに改行をつける
const nl2br = (str) => {
  var res = str.replace(/\r\n/g, "<br />");
  res = res.replace(/(\n|\r)/g, "<br />");
  return res;
}

// 今日の日付を取得(yyyy-mm-dd)
const getToday = () => {
  const today = new Date();
  var yyyy = today.getFullYear();
  var mm = ('0' + (today.getMonth()+1)).slice(-2);
  var dd = ('0' + today.getDate()).slice(-2);
  return (yyyy + '-' + mm + '-' + dd);
}

export { nl2br, getToday}