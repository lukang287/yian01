// pages/list/list.js
var list;

Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.request({
      url: config.service.voiceListUrl, //仅为示例，并非真实的接口地址
      data: {},
      method: 'GET', //必须为大写（例如：POST）
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        // console.log(res.data);
        let data = res.data;
        if (typeof data === 'string') {
          data = JSON.parse(data);
        }
        if(data.code != 0){
          console.error(data);
          util.showModel('获取语言列表失败', data);
          return;
        }
        list = data.list;
        // id=res.data.data.list.channel_id;
        // console.log(id);
      }
    })
    this.setData({
      list: list
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})