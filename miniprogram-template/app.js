App({
  globalData: {
    // 本地开发配置
    apiBase: 'http://localhost:8001/api/v1',
    webBase: 'http://localhost:8001',
    token: null,
    user: null
  },

  onLaunch() {
    console.log('小程序启动')

    // 检查登录状态
    const token = wx.getStorageSync('token')
    const user = wx.getStorageSync('user')

    if (token) {
      this.globalData.token = token
      this.globalData.user = user
      this.checkToken()
    }
  },

  // 验证 Token 是否有效
  checkToken() {
    wx.request({
      url: this.globalData.apiBase + '/user',
      method: 'GET',
      header: {
        'Authorization': 'Bearer ' + this.globalData.token
      },
      success: (res) => {
        if (res.data.success) {
          this.globalData.user = res.data.data
          wx.setStorageSync('user', res.data.data)
        }
      },
      fail: () => {
        // Token 失效，清除并跳转登录
        this.clearLoginData()
        wx.reLaunch({
          url: '/pages/login/index'
        })
      }
    })
  },

  // 清除登录数据
  clearLoginData() {
    wx.removeStorageSync('token')
    wx.removeStorageSync('user')
    this.globalData.token = null
    this.globalData.user = null
  }
})
