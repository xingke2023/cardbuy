App({
  globalData: {
    // 开发模式开关：true = 本地开发，false = 生产环境
    isDev: true,

    // 根据开发模式自动选择配置
    get apiBase() {
      return this.isDev
        ? 'http://localhost:8001/api/v1'  // 本地开发
        : 'https://cardbuy.xingke888.com/api/v1'  // 生产环境
    },

    get webBase() {
      return this.isDev
        ? 'http://localhost:8001'  // 本地开发
        : 'https://cardbuy.xingke888.com'  // 生产环境
    },

    token: null,
    user: null
  },

  onLaunch() {
    console.log('小程序启动')
    console.log('========================================')
    console.log('当前模式:', this.globalData.isDev ? '本地开发' : '生产环境')
    console.log('API地址:', this.globalData.apiBase)
    console.log('WebView地址:', this.globalData.webBase)
    console.log('========================================')

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
