import React, { useState, useEffect } from 'react';
import api from '../services/api';

function ActiveLoans({ userId }) {
    const [loans, setLoans] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
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

        fetchLoans();
    }, [userId]);

    if (loading) return <p>Carregando...</p>;

    return (
        <div>
            <h2>Empréstimos Ativos</h2>
            {loans.length === 0 ? (
                <p>Nenhum empréstimo ativo.</p>
            ) : (
                <ul>
                    {loans.map((loan) => (
                        <li key={loan.id}>Livro: {loan.title}</li>
                    ))}
                </ul>
            )}
        </div>
    );
}

export default ActiveLoans;