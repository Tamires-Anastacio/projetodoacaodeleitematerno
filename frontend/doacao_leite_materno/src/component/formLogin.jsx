import React, { useState } from "react";
import { Button, Form } from "react-bootstrap";


 function FormLog() {
  const [formData, setFormData] = useState({
    cpf: "",
    email: "",
    senha: "",
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch(
      "http://localhost/projetodoacaodeleitematerno/backend/processa_login.php",
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include", // importante para sessão/cookies
        body: JSON.stringify(formData),
      }
    );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      if (data.success) {
        // Redireciona para a página restrita
        window.location.href = "http://localhost/projetodoacaodeleitematerno/backend/restrita.php";
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error("Erro ao enviar:", error);
      alert("Erro ao enviar os dados: " + error.message);
    }
  };;

  return (
    <div className="log">
      <h1 className="protest-guerrilla-regular mb-4">Login</h1>
     
      <Form onSubmit={handleSubmit}>
        <Form.Group className="mb-3">
          <Form.Label>CPF</Form.Label>
          <Form.Control
            type="text"
            name="cpf"
            maxLength="12"
            value={formData.cpf}
            onChange={handleChange}
            required
          />
        </Form.Group>



        <Form.Group className="mb-3">
          <Form.Label>Email</Form.Label>
          <Form.Control
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Senha</Form.Label>
          <Form.Control
            type="password"
            name="senha"
            value={formData.senha}
            onChange={handleChange}
            required
          />
        </Form.Group>

        
        

        <Button variant="success" type="submit" className="w-100">
          Logar
        </Button>
      </Form>
    </div>
  );
}

export default FormLog;
