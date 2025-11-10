const app = getApp()

Page({
  data: {
    url: ''
  },

  onLoad(options) {
    let url = options.url ? decodeURIComponent(options.url) : ''

    // 如果没有传入 URL，默认跳转到首页
    if (!url) {
      const token = wx.getStorageSync('token')
      url = `${app.globalData.webBase}/?token=${token}`
    }

    this.setData({ url })

    console.log('WebView 加载:', url)
  },

  // 接收 WebView 消息
  handleMessage(e) {
    console.log('收到 WebView 消息:', e.detail.data)

    const data = e.detail.data[0]

    if (!data) return

    // 处理不同类型的消息
    switch (data.action) {
      case 'navigateBack':
        wx.navigateBack()
        break

      case 'navigateTo':
        wx.navigateTo({
          url: data.url
        })
        break

      case 'redirectTo':
        wx.redirectTo({
          url: data.url
        })
        break

      case 'pay':
        // 跳转到支付页面
        wx.navigateTo({
          url: `/pages/payment/index?orderId=${data.orderId}&amount=${data.amount}&desc=${data.desc || ''}`
        })
        break

      case 'logout':
        // 退出登录
        app.clearLoginData()
        wx.reLaunch({
          url: '/pages/login/index'
        })
        break
    }
  }
})
