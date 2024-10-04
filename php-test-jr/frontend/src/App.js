import React, { useState } from 'react';
import styled from 'styled-components';
import ActiveLoans from './components/ActiveLoans';
import AvailableBooks from './components/AvailableBooks';

const AppContainer = styled.div`
  font-family: Arial, sans-serif;
`;

const Navigation = styled.nav`
  background-color: #2c3e50;
  padding: 10px 0;
`;

const NavButton = styled.button`
  background-color: ${props => props.active ? '#3498db' : 'transparent'};
  color: white;
  border: none;
  padding: 10px 20px;
  margin: 0 10px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;

  &:hover {
    background-color: #3498db;
  }
`;

function App() {
  const [activeTab, setActiveTab] = useState('loans');

  return (
    <AppContainer>
      <Navigation>
        <NavButton
          active={activeTab === 'loans'}
          onClick={() => setActiveTab('loans')}
        >
          Empréstimos Ativos
        </NavButton>
        <NavButton
          active={activeTab === 'books'}
          onClick={() => setActiveTab('books')}
        >
          Livros Disponíveis
        </NavButton>
      </Navigation>
      {activeTab === 'loans' && <ActiveLoans userId={1} />}
      {activeTab === 'books' && <AvailableBooks />}
    </AppContainer>
  );
}

export default App;