import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, Button, Breadcrumbs, TextField } from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogTitle, DialogContentText, FormControl, RadioGroup, Slide, Radio, FormControlLabel } from '@material-ui/core';
import { Card, CardActionArea, CardContent } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import AppMenuDriver from '../components/MenuDriver';
import Services from "../Services";
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import { faPhone } from '@fortawesome/free-solid-svg-icons'
import { faWaze, faGoogle, faWhatsapp } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    BrowserView,
} from "react-device-detect";
import { If } from 'react-if'
import ReactTimeout from 'react-timeout'

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

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});


function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

class OrderDriver extends Component {
    state = {
        pedido: [],
        historico: [],
        produtos: [],
        menu: false,
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        openTransfer: false,
        openmodal: false,
        mensagem: '',
        entregadores: [],
        id_driver_transferencia: '',
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0);
        const geolocation = navigator.geolocation.getCurrentPosition(this.onSuccess);
        this.exibePedido()
    }

    exibePedido = () => {
        let params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.pedidoInternaEntregador(this, usuario.id, token, params.id)
        Services.transferenciaEntregadores(this, usuario.id, token, params.id)
    }

    onSuccess = (position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        console.log(latitude);

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.entregadoresGeolocalizacao(this, usuario.id, token, latitude, longitude);
    };

    responseEntregadoresGeolocalizacao(response) {
        this.reload = this.props.setInterval(this.reload, 60000)
    }

    responseTransferenciaEntregadores(response) {
        if (response.success === 'true') {
            this.setState({ entregadores: response.entregadores })
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
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
        let params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        let observacoes = this.state.mensagem;

        Services.cancelarPedidoEntregador(this, usuario.id, token, params.id, observacoes)
    }

    aceptOrder = () => {
        let params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.aceitarPedidoEntregador(this, usuario.id, token, params.id)
    }

    finishOrder = () => {
        let params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.entregarPedidoEntregador(this, usuario.id, token, params.id)
    }

    responsePedidoCancelar(response) {
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

    responsePedidoAceitar(response) {
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
            this.setState({ alertMessage: "Tudo certo! Pedido Aceito!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    responsePedidoEntregar(response) {
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
            this.setState({ alertMessage: "Tudo certo! Pedido entregue!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    handleClose = () => {
        this.setState({ openmodal: false })
        window.location.reload();
    }

    handleInputChange = (event) => {
        if (event.target.name === 'mensagem') {
            this.setState({ mensagem: event.target.value })
        }
    }

    handleCloseAlert = () => {
        if (this.state.alertStatus === 'success' && this.state.id_driver_transferencia !== ''){
            this.setState({redirect: 'home'})
            return
        }

        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    handleCloseTransfer = () => {
        this.setState({ openTransfer: false })
        this.setState({ id_driver_transferencia: '' })
        window.location.reload();
    }

    changeDriver = (e) => {
        this.setState({ id_driver_transferencia: e.target.value });
    }

    transferirPedido = () =>{
        this.setState({ openTransfer: false })
        let params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        let observacoes = this.state.mensagem;
        let entregador = this.state.id_driver_transferencia;

        Services.transferenciaPedido(this, usuario.id, token, params.id, observacoes, entregador)
    }

    responseTransferenciaPedido(response) {
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
            this.setState({ alertMessage: "Tudo certo! Pedido transferido!" })
            this.setState({ alertStatus: 'success' })
            return
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

                <AppMenuDriver />

                <main>
                    <div className={classes.topSpace} />

                    <Container maxWidth="lg" className={classes.container}>
                        <Link to='/homeentregador'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
              </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} align="center">
                                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                                    Detalhes do pedido nº: #{pedido.id}
                                </Typography>
                            </Grid>

                            <If condition={pedido.status === 1 || pedido.status === '1'}>
                                <Grid item container xs={12} lg={12}>
                                    <Grid item xs={12} lg={12}>
                                        <Button
                                            variant="contained"
                                            className="button-driver-acept"
                                            size="large"
                                            fullWidth
                                            disabled={this.state.loadingForm}
                                            onClick={this.aceptOrder}
                                        >Aceitar Pedido</Button>
                                    </Grid>
                                </Grid>
                            </If>
                            {/* <If condition={pedido.status === 2 || pedido.status === '2'}>
                                <Grid item container xs={12} lg={12} spacing={2}>
                                    <Grid item xs={12} lg={6}>
                                        <Button
                                            color="secondary"
                                            variant="contained"
                                            size="large"
                                            fullWidth
                                            disabled={this.state.loadingForm}
                                            onClick={this.cancelOrder}
                                        >Cancelar pedido</Button>
                                    </Grid>
                                    <Grid item xs={12} lg={6}>
                                        <Button
                                            color="primary"
                                            variant="contained"
                                            size="large"
                                            fullWidth
                                            disabled={this.state.loadingForm}
                                            onClick={this.startOrder}
                                        >Iniciar Entrega</Button>
                                    </Grid>
                                </Grid>
                            </If> */}
                            <If condition={pedido.status === 3 || pedido.status === '3' ||
                                pedido.status === 2 || pedido.status === '2'}>
                                <Grid container item xs={12} lg={12}>
                                    <Grid item xs={12} lg={12}>
                                        <Button
                                            className="button-driver-send"
                                            variant="contained"
                                            size="large"
                                            fullWidth
                                            disabled={this.state.loadingForm}
                                            onClick={this.finishOrder}
                                        >Marcar como entregue</Button>
                                    </Grid>
                                </Grid>
                            </If>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            Cliente: {pedido.cliente_nome}
                                        </Typography>

                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            Status do pedido: {pedido.status_titulo}
                                        </Typography>

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
                                            Contato do cliente
                                            </Typography>

                                        <Typography variant="body2" color="secondary">
                                            Telefone:
                                            </Typography>
                                        <a  href={"tel:+55" + pedido.cliente_telefone_celular} className={classes.titleSmall} target="_blank">
                                            <Typography variant="body1" color="primary" paragraph> <FontAwesomeIcon icon={faPhone} /> Ligar</Typography>
                                        </a>
                                        <Typography variant="body2" color="secondary">
                                            Whatsapp:
                                            </Typography>
                                        <a href={"https://api.whatsapp.com/send?phone=55" + pedido.cliente_telefone_celular + "&text=Oi%2C%20tudo%20bem%3F%20Sou%20entregador%20do%20Gelada%20em%20Casa%20App%20e%20quero%20falar%20sobre%20o%20pedido%3A%20" + pedido.id} target="_blank">
                                            <Typography variant="body1" color="primary" paragraph> <FontAwesomeIcon icon={faWhatsapp} /> Enviar mensagem</Typography>
                                        </a>
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

                                        {/* <Typography variant="body1" color="secondary" align="center" paragraph>
                                            Valor total do pedido: R$ {pedido.total_pedido}
                                        </Typography> */}

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

                                            <Typography variant="body1" color="secondary" align="center" paragraph>
                                                {pedido.cupom}
                                            </Typography>
                                        </CardContent>
                                    </Card>
                                </Grid>
                            </If>

                            <If condition={pedido.observacoes !== ""}>
                                <Grid item xs={12} lg={12}>
                                    <Card raised={true} className={classes.root} >
                                        <CardContent>
                                            <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                                Observações do pedido:
                                            </Typography>

                                            <Typography variant="body1" color="secondary" align="center" paragraph>
                                                {pedido.observacoes}
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
                                            Nome do cliente: {pedido.cliente_nome}
                                        </Typography>

                                        <Typography variant="body1" color="secondary" paragraph>
                                            Distância aproximada: {pedido.distancia} km
                                        </Typography>

                                        <Typography variant="body1" color="secondary" paragraph>
                                            Endereço: {pedido.endereco} , {pedido.numero} - {pedido.complemento}
                                        </Typography>
                                        <Typography variant="body1" color="secondary" paragraph>
                                            Bairro: {pedido.bairro}
                                        </Typography>
                                        <Typography variant="body1" color="secondary" paragraph>
                                            CEP: {pedido.cep} , {pedido.cidade}
                                        </Typography>

                                        <a href={'https://www.google.com/maps/dir/?api=1&destination=' + pedido.latitude + ',' + pedido.longitude + '&travelmode=driving'}  target="_blank" >
                                            <Typography variant="body1" color="primary" paragraph> <FontAwesomeIcon icon={faGoogle} /> Ver rota no Google Maps</Typography>
                                        </a>

                                        <a  href={'https://www.waze.com/ul?ll=' + pedido.latitude + '%2C' + pedido.longitude + '&navigate=yes'} target="_blank">
                                            <Typography variant="body1" color="primary" paragraph> <FontAwesomeIcon icon={faWaze} /> Ver rota no Waze</Typography>
                                        </a>

                                    </CardContent>
                                </Card>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            Relatar um problema (apenas em caso de cancelamento ou transferência de pedido)
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
                            </Grid>

                            <If condition={pedido.status === 1 || pedido.status === '1' || pedido.status === 3 || pedido.status === '3' ||
                                pedido.status === 2 || pedido.status === '2'}>
                                <Grid container item xs={12} lg={12} spacing={2}>
                                    <Grid item xs={12} lg={6} >
                                        <Button
                                            color="secondary"
                                            variant="contained"
                                            size="large"
                                            fullWidth
                                            disabled={this.state.loadingForm}
                                            onClick={this.cancelOrder}
                                        >Cancelar pedido</Button>
                                    </Grid>
                                    <Grid item xs={12} lg={6}>
                                        <Button
                                            color="primary"
                                            variant="contained"
                                            size="large"
                                            fullWidth
                                            disabled={this.state.loadingForm}
                                            onClick={() => this.setState({ openTransfer: true })}
                                        >Transferir pedido</Button>
                                    </Grid>
                                </Grid>
                            </If>

                        </Grid>
                    </Container>

                    <Dialog
                        open={this.state.openmodal}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                    >
                        <DialogTitle id="alert-dialog-title">
                            <Typography component="h2" variant="h5" color="primary" align="center">
                                Cancelamento de pedido
                    </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <Typography component="p" variant="body2" color="secondary" align="center">
                                Tem certeza que deseja cancelar este pedido?
                    </Typography>
                        </DialogContent>
                        <DialogActions >
                            <Button variant="contained" color="secondary" onClick={this.cancelOrderConfirm}>
                                Quero cancelar
                            </Button>
                            <Button variant="contained" color="primary" onClick={this.handleClose}>
                                Manter pedido
                            </Button>
                        </DialogActions>
                    </Dialog>

                    <Dialog
                        open={this.state.openTransfer}
                        TransitionComponent={Transition}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                        onClose={this.handleCloseTransfer}
                    >
                        <DialogTitle id="alert-dialog-title">
                            <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>
                                Tem certeza que deseja transferir este pedido?
                    </Typography>
                            <Typography component="h3" variant="body1" align="center" color="secondary">
                                Escolha um motorista para receber o pedido:
                            </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <FormControl component="fieldset">
                                <RadioGroup aria-label="entregadores" name="entregadores" value={this.state.id_driver_transferencia} onChange={this.changeDriver}>
                                    {this.state.entregadores.map((entregador) => {
                                        return <FormControlLabel value={entregador.id} control={<Radio />} label={entregador.nome} />
                                    })}
                                </RadioGroup>
                            </FormControl>
                        </DialogContent>
                        <DialogActions >
                            <Button variant="contained" color="secondary" fullWidth onClick={this.handleCloseTransfer}>
                                Cancelar
                            </Button>
                            <Button variant="contained" color="primary" fullWidth onClick={this.transferirPedido}>
                                Transferir
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

OrderDriver.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default ReactTimeout(withStyles(useStyles)(OrderDriver));