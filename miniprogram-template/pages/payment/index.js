const app = getApp()

Page({
  data: {
    orderId: '',
    amount: 0,
    orderDesc: '',
    loading: false
  },

  onLoad(options) {
    this.setData({
      orderId: options.orderId,
      amount: options.amount || 0,
      orderDesc: options.desc || '订单支付'
    })
  },

  handlePay() {
    this.setData({ loading: true })

    // TODO: 实际项目中需要对接微信支付
    // 1. 向后端请求支付参数
    wx.request({
      url: app.globalData.apiBase + '/payment/prepare',
      method: 'POST',
      header: {
        'Authorization': 'Bearer ' + wx.getStorageSync('token')
      },
      data: {
        order_id: this.data.orderId
      },
      success: (res) => {
        if (res.data.success) {
          // 2. 调用微信支付
          wx.requestPayment({
            timeStamp: res.data.data.timeStamp,
            nonceStr: res.data.data.nonceStr,
            package: res.data.data.package,
            signType: res.data.data.signType,
            paySign: res.data.data.paySign,
            success: (payRes) => {
              wx.showToast({ title: '支付成功', icon: 'success' })

              // 3. 跳转到订单详情
              setTimeout(() => {
                const url = encodeURIComponent(
                  `${app.globalData.webBase}/orders/${this.data.orderId}?token=${wx.getStorageSync('token')}`
                )
                wx.redirectTo({
                  url: '/pages/webview/index?url=' + url
                })
              }, 1500)
            },
            fail: (err) => {
              if (err.errMsg === 'requestPayment:fail cancel') {
                wx.showToast({ title: '已取消支付', icon: 'none' })
              } else {
                wx.showToast({ title: '支付失败', icon: 'none' })
              }
            }
          })
        } else {
          wx.showToast({ title: '获取支付参数失败', icon: 'none' })
        }
      },
      fail: () => {
        wx.showToast({ title: '网络错误', icon: 'none' })
      },
      complete: () => {
        this.setData({ loading: false })
      }
    })

    // 开发阶段模拟支付成功（实际项目删除此代码）
    setTimeout(() => {
      this.setData({ loading: false })
      wx.showModal({
        title: '提示',
        content: '这是演示模式，实际需要对接微信支付',
        success: (res) => {
          if (res.confirm) {
            const url = encodeURIComponent(
              `${app.globalData.webBase}/orders/${this.data.orderId}?token=${wx.getStorageSync('token')}`
            )
            wx.redirectTo({
              url: '/pages/webview/index?url=' + url
            })
          }
        }
      })
    }, 1000)
  }
})
