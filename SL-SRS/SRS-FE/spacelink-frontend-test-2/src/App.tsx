import { Routes, Route, Link, Navigate } from 'react-router-dom'
import { Layout, Menu } from 'antd'
import Login from './pages/Login'
import Register from './pages/Register'
import Profile from './pages/Profile'
import ProtectedRoute from './components/ProtectedRoute'

const { Header, Content } = Layout

function App() {
  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Header>
        <Menu theme="dark" mode="horizontal">
          <Menu.Item key="home"><Link to="/">Home</Link></Menu.Item>
          <Menu.Item key="login"><Link to="/login">Login</Link></Menu.Item>
          <Menu.Item key="register"><Link to="/register">Register</Link></Menu.Item>
          <Menu.Item key="profile"><Link to="/profile">Profile</Link></Menu.Item>
        </Menu>
      </Header>
      <Content style={{ padding: '24px' }}>
        <Routes>
          <Route path="/" element={<div>Home - Chọn Login/Register để test API</div>} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/profile" element={<ProtectedRoute><Profile /></ProtectedRoute>} />
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </Content>
    </Layout>
  )
}

export default App