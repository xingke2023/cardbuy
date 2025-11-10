const app = getApp()

Page({
  data: {
    email: '',
    password: '',
    loading: false,
    wechatLoading: false,
    showOtherLogin: false  // 默认不显示其他登录方式
  },

  onLoad() {
    // 检查是否已登录
    this.checkLoginStatus()
  },

  // 检查登录状态
  checkLoginStatus() {
    const token = wx.getStorageSync('token')

    if (token) {
      // 已登录，验证 token 是否有效
      wx.request({
        url: app.globalData.apiBase + '/user',
        method: 'GET',
        header: {
          'Authorization': 'Bearer ' + token
        },
        success: (res) => {
          if (res.data.success) {
            // Token 有效，直接跳转到首页
            console.log('已登录，自动跳转到首页')
            wx.redirectTo({
              url: '/pages/webview/index?url=' +
                   encodeURIComponent(app.globalData.webBase + '/?token=' + token)
            })
          } else {
            // Token 无效，清除登录信息
            app.clearLoginData()
          }
        },
        fail: () => {
          // 验证失败，清除登录信息
          app.clearLoginData()
        }
      })
    }
  },

  onEmailInput(e) {
    this.setData({ email: e.detail.value })
  },

  onPasswordInput(e) {
    this.setData({ password: e.detail.value })
  },

  // 切换其他登录方式显示/隐藏
  toggleOtherLogin() {
    this.setData({
      showOtherLogin: !this.data.showOtherLogin
    })
  },

  handleLogin() {
    const { email, password } = this.data

    if (!email || !password) {
      wx.showToast({ title: '请输入邮箱和密码', icon: 'none' })
      return
    }

    this.setData({ loading: true })

    wx.request({
      url: app.globalData.apiBase + '/login',
      method: 'POST',
      data: { email, password },
      success: (res) => {
        console.log('登录响应:', res)

        if (res.data.success) {
          const token = res.data.data.token

          // 保存 token
          wx.setStorageSync('token', token)
          wx.setStorageSync('user', res.data.data.user)
          app.globalData.token = token
          app.globalData.user = res.data.data.user

          wx.showToast({ title: '登录成功', icon: 'success' })

          // 跳转到首页（WebView）
          setTimeout(() => {
            wx.redirectTo({
              url: '/pages/webview/index?url=' +
                   encodeURIComponent(app.globalData.webBase + '/?token=' + token)
            })
          }, 1500)
        } else {
          wx.showToast({ title: res.data.message || '登录失败', icon: 'none' })
        }
      },
      fail: (err) => {
        console.error('登录失败:', err)
        wx.showToast({ title: '网络错误', icon: 'none' })
      },
      complete: () => {
        this.setData({ loading: false })
      }
    })
  },

  goRegister() {
    // 注册也可以用 WebView
    const url = encodeURIComponent(app.globalData.webBase + '/register')
    wx.navigateTo({
      url: '/pages/webview/index?url=' + url
    })
  },

  goForgotPassword() {
    // 忘记密码用 WebView
    const url = encodeURIComponent(app.globalData.webBase + '/forgot-password')
    wx.navigateTo({
      url: '/pages/webview/index?url=' + url
    })
  },

  // 微信一键登录
  handleWechatLogin() {
    this.setData({ wechatLoading: true })

    // 1. 调用 wx.login 获取 code
    wx.login({
      success: (loginRes) => {
        if (loginRes.code) {
          // 2. 将 code 发送到后端
          wx.request({
            url: app.globalData.apiBase + '/wechat/login',
            method: 'POST',
            data: {
              code: loginRes.code
            },
            success: (res) => {
              console.log('微信登录响应:', res)

              if (res.data.success) {
                const token = res.data.data.token
                const user = res.data.data.user
                const isNewUser = res.data.data.is_new_user

                // 保存 token 和用户信息
                wx.setStorageSync('token', token)
                wx.setStorageSync('user', user)
                app.globalData.token = token
                app.globalData.user = user

                wx.showToast({ title: '登录成功', icon: 'success' })

                // 跳转到首页
                setTimeout(() => {
                  wx.redirectTo({
                    url: '/pages/webview/index?url=' +
                         encodeURIComponent(app.globalData.webBase + '/?token=' + token)
                  })
                }, 1500)

                // 如果是新用户，可以引导完善资料
                if (isNewUser) {
                  console.log('新用户首次登录')
                }
              } else {
                wx.showToast({
                  title: res.data.message || '微信登录失败',
                  icon: 'none'
                })
              }
            },
            fail: (err) => {
              console.error('微信登录失败:', err)
              wx.showToast({ title: '网络错误', icon: 'none' })
            },
            complete: () => {
              this.setData({ wechatLoading: false })
            }
          })
        } else {
          console.error('获取 code 失败:', loginRes.errMsg)
          wx.showToast({ title: '微信登录失败', icon: 'none' })
          this.setData({ wechatLoading: false })
        }
      },
      fail: (err) => {
        console.error('wx.login 失败:', err)
        wx.showToast({ title: '微信授权失败', icon: 'none' })
        this.setData({ wechatLoading: false })
      }
    })
  }
})
