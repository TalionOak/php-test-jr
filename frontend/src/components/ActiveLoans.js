import React, { useState, useEffect } from 'react';
import api from '../services/api';
import styled from 'styled-components';

const Container = styled.div`
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
`;

const Title = styled.h2`
  color: #2c3e50;
  border-bottom: 2px solid #3498db;
  padding-bottom: 10px;
`;

const LoanList = styled.ul`
  list-style-type: none;
  padding: 0;
`;

const LoanItem = styled.li`
  background-color: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  margin-bottom: 20px;
  padding: 20px;
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
`;

const BookInfo = styled.p`
  margin: 5px 0;
  color: #34495e;
`;

const LoanDate = styled.p`
  color: #7f8c8d;
  font-style: italic;
`;

const LoadingMessage = styled.p`
  text-align: center;
  color: #7f8c8d;
  font-size: 18px;
`;

const ReturnButton = styled.button`
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-top: 10px;

  &:hover {
    background-color: #2980b9;
  }

  &:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
  }
`;

function ActiveLoans({ userId }) {
    const [loans, setLoans] = useState([]);
    const [loading, setLoading] = useState(true);

    const fetchLoans = async () => {
        try {
            const response = await api.get(`/user/${userId}/loans`);
            setLoans(response.data);
        } catch (error) {
            console.error('Erro ao buscar empréstimos:', error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchLoans();
    }, [userId]);

    const handleReturn = async (bookId) => {
        try {
            await api.get(`/return/${bookId}`);
            alert('Livro devolvido com sucesso!');
            // Atualiza a lista de empréstimos após a devolução
            fetchLoans();
        } catch (error) {
            console.error('Erro ao devolver o livro:', error);
            alert('Erro ao devolver o livro. Por favor, tente novamente.');
        }
    };

    if (loading) return <LoadingMessage>Carregando...</LoadingMessage>;

    return (
        <Container>
            <Title>Empréstimos Ativos</Title>
            {loans.length === 0 ? (
                <BookInfo>Nenhum empréstimo ativo.</BookInfo>
            ) : (
                <LoanList>
                    {loans.map((loan) => (
                        <LoanItem key={loan.id}>
                            <BookTitle>{loan.book.title}</BookTitle>
                            <BookInfo>Autor: {loan.book.author}</BookInfo>
                            <BookInfo>ISBN: {loan.book.isbn}</BookInfo>
                            <BookInfo>Ano de publicação: {loan.book.publication_year}</BookInfo>
                            <LoanDate>
                                Data do empréstimo: {new Date(loan.loan_date).toLocaleDateString()}
                            </LoanDate>
                            <ReturnButton onClick={() => handleReturn(loan.book.id)}>
                                Devolver Livro
                            </ReturnButton>
                        </LoanItem>
                    ))}
                </LoanList>
            )}
        </Container>
    );
}

export default ActiveLoans;