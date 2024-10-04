import React from 'react';
import './App.css';
import ActiveLoans from './components/ActiveLoans';

function App() {
  const userId = 1; 

  return (
    <div className="App">
      <main>
        <ActiveLoans userId={userId} />
      </main>
    </div>
  );
}

export default App;
