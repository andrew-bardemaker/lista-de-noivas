import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, Button, Breadcrumbs, TextField } from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogTitle, DialogContentText } from '@material-ui/core';
import { Card, CardContent } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import ReactTimeout from 'react-timeout';
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    BrowserView,
} from "react-device-detect";
import { If } from 'react-if'
import Cached from '@material-ui/icons/Cached';

const theme = createMuiTheme({
    palette: {
        primary: {
            light: '#eebf2f',
            main: '#eebf2f',
            dark: '#eebf2f',
            contrastText: '#1f3a4e',
        },
        secondary: {
            light: '#1f3a4e',
            main: '#1f3a4e',
            dark: '#1f3a4e',
            contrastText: '#fff',
        },
    },
});


function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

class OrdersDetails extends Component {
    state = {
        pedido: [],
        historico: [],
        produtos: [],
        menu: false,
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        openmodal: false,
        redirect: '',
        mensagem: '',
        loadingForm: false,
    }

    componentDidMount() {
        var params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.pedidoInterna(this, usuario.id, token, params.id)
    }

    componentWillUnmount() {
        this.props.clearInterval(this.reload);
    }

    reload = () => {
        window.location.reload();
    }

    responsePedidoInterna(response) {
        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }

        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }

        if (response.success === 'true') {
            this.reload = this.props.setInterval(this.reload, 120000)
            this.setState({ pedido: response.pedido[0] })
            this.setState({ historico: response.pedido[0].historico_status })
            this.setState({ produtos: response.pedido[0].produtos })
        }
    }

    cancelOrder = (e) => {
        this.setState({ deleteAdress: e })
        this.setState({ openmodal: true })
    }

    cancelOrderConfirm = () => {
        this.setState({ loadingForm: true })
        let params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let mensagem = this.state.mensagem;

        Services.pedidoCancelar(this, usuario.id, token, params.id, mensagem)
    }

    responsePedidoCancelar(response) {
        this.setState({ loadingForm: false })

        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }

        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }

        if (response.success === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Tudo certo! Pedido cancelado!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    handleClose = () => {
        this.setState({ openmodal: false })
        window.location.reload();
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    handleInputChange = (event) => {
        if (event.target.name === 'mensagem') {
            this.setState({ mensagem: event.target.value })
        }
    }

    render() {
        const { classes } = this.props;
        const pedido = this.state.pedido;
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return <div className={classes.root} id="top">
            <ThemeProvider theme={theme}>

                <AppMenuLogged />

                <main>
                    <div className={classes.topSpace} />

                    <Container maxWidth="lg" className={classes.container}>
                        <Link to='/pedidos'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
              </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="lg" className={classes.container}>
                        <Breadcrumbs aria-label="breadcrumb">
                            <Link to='/home' >
                                Home
                            </Link>
                            <Link to="/pedidos">
                                Pedidos
                            </Link>
                            <Link to={"/notificacao" + this.state.pedido.id}>
                                Pedido nº: {pedido.id}
                            </Link>
                        </Breadcrumbs>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} align="center">
                                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                                    Detalhes do pedido
                                    </Typography>
                            </Grid>

                            <Grid item xs={false} lg={4}></Grid>
                            <Grid item xs={12} lg={4}>
                                <Button
                                    color="secondary"
                                    variant="contained"
                                    fullWidth
                                    endIcon={<Cached />}
                                    onClick={() => window.location.reload()}
                                >Atualizar a página</Button></Grid>
                            <Grid item xs={false} lg={4}></Grid>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            Status do pedido: {pedido.status_titulo}
                                        </Typography>

                                        <If condition={pedido.status === 1 || pedido.status === '1'}>
                                            <Typography gutterBottom component="body2" color="primary">
                                                Teu pedido está sendo direcionado para o nosso carro mais próximo, aguarde...
                      </Typography>
                                        </If>
                                        <If condition={pedido.status === 2 || pedido.status === '2' || pedido.status === 3 || pedido.status === '3'}>
                                            <Typography gutterBottom component="body2" color="primary">
                                                Teu pedido vai chegar em breve, um entregador Gelada em Casa já está a caminho!
                      </Typography>
                                        </If>

                                        {this.state.historico.map((historico) => {
                                            return <Typography variant="body2" color="secondary" component="p" key={historico.status}>
                                                - {historico.status_titulo} : {historico.data_hora_registro}
                                            </Typography>
                                        })}
                                    </CardContent>
                                </Card>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >

                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            Produtos do pedido:
                                            </Typography>
                                        {this.state.produtos.map((produtos) => {
                                            return <Typography variant="body2" color="secondary" component="p" key={produtos.id}>
                                                - {produtos.qntd}X {produtos.titulo}
                                            </Typography>
                                        })}

                                        <Typography variant="body1" color="secondary" align="center" paragraph>
                                            Valor total do pedido: R$ {pedido.total_pedido}
                                        </Typography>

                                    </CardContent>

                                </Card>
                            </Grid>
                            <If condition={pedido.cupom !== ""}>
                                <Grid item xs={12} lg={12}>
                                    <Card raised={true} className={classes.root} >
                                        <CardContent>
                                            <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                              Cupom:
                                            </Typography>

                                            <Typography variant="body1" color="secondary" align="" paragraph>
                                                {pedido.cupom}
                                            </Typography>
                                        </CardContent>
                                    </Card>
                                </Grid>
                            </If>
                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >

                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            Endereço de entrega
                                            </Typography>

                                        <Typography variant="body1" color="secondary" paragraph>
                                            {pedido.endereco} , {pedido.numero} - {pedido.complemento}<br /> {pedido.bairro} - {pedido.cep} , {pedido.cidade}
                                        </Typography>
                                    </CardContent>

                                </Card>
                            </Grid>

                            <If condition={pedido.status === 1 || pedido.status === '1'}>
                                <Grid item xs={12} lg={12}>
                                    <Card raised={true} className={classes.root} >
                                        <CardContent>
                                            <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                                Relatar um problema (apenas em caso de cancelamento)
                                            </Typography>

                                            <TextField
                                                name="mensagem"
                                                variant="outlined"
                                                required
                                                fullWidth
                                                multiline
                                                rows={4}
                                                id="mensagem"
                                                label="Mensagem"
                                                color="secondary"
                                                onChange={this.handleInputChange}
                                            />
                                        </CardContent>
                                    </Card>
                                </Grid></If>

                            <Grid item xs={12} lg={12}>
                                <If condition={pedido.status === 1 || pedido.status === '1'}>
                                    <Button
                                        color="primary"
                                        variant="contained"
                                        size="large"
                                        className="w-100"
                                        disabled={this.state.loadingForm}
                                        onClick={this.cancelOrder}
                                    >Cancelar pedido</Button>
                                </If>
                                <If condition={pedido.status === 4 || pedido.status === '4' ||
                                    pedido.status === 5 || pedido.status === '5'}>
                                    <Link to={'/chamados'}><Button
                                        color="primary"
                                        variant="contained"
                                        size="large"
                                        className="w-100"
                                        disabled={this.state.loadingForm}
                                    >Quero relatar um problema, fazer uma sugestão ou escrever um elogio</Button></Link>
                                </If>
                            </Grid>

                        </Grid>
                    </Container>

                    <Dialog
                        open={this.state.openmodal}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                    >
                        <DialogTitle id="alert-dialog-title">
                            <Typography component="h2" variant="h5" align="center" className={classes.title}>
                                Cancelamento de pedido
                    </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <DialogContentText className={classes.ageModalText} id="alert-dialog-description">
                                Tem certeza que deseja cancelar o seu pedido?
          </DialogContentText>
                        </DialogContent>
                        <DialogActions >
                            <Button color="secondary" variant="contained" onClick={this.cancelOrderConfirm} disabled={this.state.loadingForm}>
                                Quero cancelar
          </Button>
                            <Button color="primary" variant="contained" onClick={this.handleClose} >
                                Manter pedido
                        </Button>
                        </DialogActions>
                    </Dialog>

                    <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                        <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                            {this.state.alertMessage}
                        </Alert>
                    </Snackbar>
                </main>

                <BrowserView>
                    <Footer />
                </BrowserView>
            </ThemeProvider>
        </div >
    }
}

OrdersDetails.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default ReactTimeout(withStyles(useStyles)(OrdersDetails));