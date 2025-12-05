# XBoard Payment Plugin - MugglePay
MugglePay is a cryptocurrency payment gateway similar to a crypto version of Alipay. Users can quickly register and obtain payment channels without complicated KYC processes. The platform primarily supports multi-chain payments such as USDT and USDC, effectively solving various payment needs.

XBoard, as a popular airport (proxy service) panel, can perfectly resolve payment issues by integrating MugglePay. Here's how to integrate it:
Installation Guide

## Download the complete MugglePay plugin folder
- Compress the folder locally into a .zip file
- Log in to your XBoard admin panel → Plugin Management
- Click "Upload Plugin" and select the zip package
- Enable the plugin after successful installation

## Payment Configuration

Register a MugglePay merchant account
- Registration [Link](merchants.mugglepay.com/user/register?ref=MPF03FE79B-D459-41F0-8155-7F29913F4F66)
- After logging in, go to API Settings → Create a new App Secret
- Return to XBoard admin panel → Payment Methods → Add Payment Method
- Fill in the following information:
  - Display Name: Customize as needed (e.g., "USDT Payment", "Crypto Payment")
  - Icon URL: Optional custom icon link
  - Notification Domain: Your XBoard panel domain (e.g., https://panel.example.com)
  - Payment Gateway: Select mugglepay
  - MugglePay App Secret: Paste the App Secret you just created in step 2
- Save and test with a small-amount order


# Xboard 支付插件 - Mugglepay
Mugglepay 是一个加密货币货币支付平台，类似于加密版的支付宝。用户无需KYC等复杂流程，即可快速注册申请支付渠道，平台主要支持USDT、USDC等多链支付，可以有效解决支付需求。

Xboard作为流行的机场搭建平台，通过集成 Mugglepay 支付可以有效解决支付问题。下面来看看如何集成：

## 安装方法
- 下载 Mugglepay 文件夹
- 本地打包成 zip格式
- 打开 Xboard 面板后台 - 插件管理
- 点击上传插件，选择zip包
- 启用插件

## 支付配置
- 注册 Mugglepay 账号 [注册地址](merchants.mugglepay.com/user/register?ref=MPF03FE79B-D459-41F0-8155-7F29913F4F66)
- 点击API接口设置，创建密钥
- 进入Xboard 面板后台 - 支付配置
- 选择添加支付方式
- 配置相关信息
  - 显示名称：按需，如USDT支付
  - 图标URL：自定义
  - 通知域名：输入你的 Xboard 面板的域名
  - 支付接口选择 mugglepay
  - Mugglepay App Secret 配置你上面创建的密钥
- 保存提交进行测试即可
