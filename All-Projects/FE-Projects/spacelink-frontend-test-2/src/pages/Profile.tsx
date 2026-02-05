import { useState, useEffect } from 'react'
import { Card, Descriptions, Button, Spin, message } from 'antd'
import { useNavigate } from 'react-router-dom'
import api from '../lib/api'

export default function Profile() {
  const [user, setUser] = useState<{ id: number; name: string; email: string } | null>(null)
  const [loading, setLoading] = useState(true)
  const navigate = useNavigate()

  useEffect(() => {
    api.get('/user').then(({ data }) => {
      setUser(data)
    }).catch(() => {
      message.error('Không thể tải thông tin user')
    }).finally(() => setLoading(false))
  }, [])

  const handleLogout = async () => {
    try {
      await api.post('/logout')
      localStorage.removeItem('token')
      message.success('Đã đăng xuất')
      navigate('/login')
    } catch {
      localStorage.removeItem('token')
      navigate('/login')
    }
  }

  if (loading) return <Spin size="large" style={{ display: 'block', margin: '48px auto' }} />

  return (
    <Card title="Thông tin user" style={{ maxWidth: 500, margin: 'auto' }}>
      {user && (
        <Descriptions column={1}>
          <Descriptions.Item label="ID">{user.id}</Descriptions.Item>
          <Descriptions.Item label="Tên">{user.name}</Descriptions.Item>
          <Descriptions.Item label="Email">{user.email}</Descriptions.Item>
        </Descriptions>
      )}
      <Button danger onClick={handleLogout} style={{ marginTop: 16 }}>
        Đăng xuất
      </Button>
    </Card>
  )
}