import React, { useState, useEffect } from 'react';
import api from '../services/api';
import styled from 'styled-components';

const Container = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
`;

const Title = styled.h2`
  color: #2c3e50;
  border-bottom: 2px solid #3498db;
  padding-bottom: 10px;
`;

const BookGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 20px;
`;

const BookCard = styled.div`
  background-color: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }
`;

const BookTitle = styled.h3`
  color: #2980b9;
  margin-top: 0;
  font-size: 1.2em;
`;

const BookInfo = styled.p`
  margin: 5px 0;
  color: #34495e;
  font-size: 0.9em;
`;

const BorrowButton = styled.button`
  background-color: #2ecc71;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-top: 10px;

  &:hover {
    background-color: #27ae60;
  }

  &:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
  }
`;

function AvailableBooks() {
  const [books, setBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  const userId = 1; // Substitua pelo ID do usuário atual

  useEffect(() => {
    const fetchBooks = async () => {
      try {
        const response = await api.get('/books/available');
        setBooks(response.data);
      } catch (error) {
        console.error('Erro ao buscar livros disponíveis:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchBooks();
  }, []);

  const handleBorrow = async (bookId) => {
    try {
      await api.get(`/borrow/${bookId}/user/${userId}`);
      // Atualiza a lista de livros após o empréstimo
      const updatedBooks = books.map(book => 
        book.id === bookId 
          ? { ...book, active_loans: book.active_loans + 1 } 
          : book
      );
      setBooks(updatedBooks);
      alert('Livro emprestado com sucesso!');
    } catch (error) {
      console.error('Erro ao emprestar o livro:', error);
      alert('Erro ao emprestar o livro. Por favor, tente novamente.');
    }
  };

  if (loading) return <p>Carregando...</p>;

  return (
    <Container>
      <Title>Livros Disponíveis</Title>
      <BookGrid>
        {books.map((book) => (
          <BookCard key={book.id}>
            <BookTitle>{book.title}</BookTitle>
            <BookInfo>Autor: {book.author}</BookInfo>
            <BookInfo>ISBN: {book.isbn}</BookInfo>
            <BookInfo>Ano de publicação: {book.publication_year}</BookInfo>
            <BookInfo>Total de cópias: {book.total_copies}</BookInfo>
            <BookInfo>Empréstimos ativos: {book.active_loans}</BookInfo>
            <BorrowButton 
              onClick={() => handleBorrow(book.id)}
              disabled={book.active_loans >= book.total_copies}
            >
              {book.active_loans >= book.total_copies ? 'Indisponível' : 'Emprestar'}
            </BorrowButton>
          </BookCard>
        ))}
      </BookGrid>
    </Container>
  );
}

export default AvailableBooks;