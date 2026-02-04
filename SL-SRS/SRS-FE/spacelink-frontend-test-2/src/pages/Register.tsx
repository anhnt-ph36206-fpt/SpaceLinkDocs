import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { Form, Input, Button, Card, message } from 'antd'
import api from '../lib/api'

export default function Register() {
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  const onFinish = async (values: { name: string; email: string; password: string; password_confirmation: string }) => {
    setLoading(true)
    try {
      const { data } = await api.post('/register', values)
      localStorage.setItem('token', data.token)
      message.success('Đăng ký thành công')
      navigate('/profile')
    } catch (err: unknown) {
      const e = err as { response?: { data?: { message?: string } } }
      message.error(e.response?.data?.message || 'Lỗi đăng ký')
    } finally {
      setLoading(false)
    }
  }

  return (
    <Card title="Đăng ký" style={{ maxWidth: 400, margin: 'auto' }}>
      <Form onFinish={onFinish} layout="vertical">
        <Form.Item name="name" label="Tên" rules={[{ required: true }]}>
          <Input />
        </Form.Item>
        <Form.Item name="email" label="Email" rules={[{ required: true }]}>
          <Input type="email" />
        </Form.Item>
        <Form.Item name="password" label="Mật khẩu" rules={[{ required: true }]}>
          <Input.Password />
        </Form.Item>
        <Form.Item name="password_confirmation" label="Xác nhận mật khẩu" rules={[{ required: true }]}>
          <Input.Password />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit" loading={loading} block>
            Đăng ký
          </Button>
        </Form.Item>
      </Form>
    </Card>
  )
}