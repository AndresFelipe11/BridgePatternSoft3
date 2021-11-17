/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package remotes;
import devices.Device;

/**
 * @version 1.0
 * @author Cristian Guerrero - Andres Cadavid
 * Class that implemments Remote´s interface and his methods, aslo implemments any methods of Device.
 */
public class BasicRemote implements Remote {
    protected Device device;

    public BasicRemote() {}

    public BasicRemote(Device device) {
        this.device = device;
    }

    @Override
    public void power() {
        System.out.println("");
        if (device.isEnabled()) {
            device.disable();
        } else {
            device.enable();
        }
    }

    @Override
    public void volumeDown() {
        System.out.println("Control remoto: botón de disminución del volumen");
        device.setVolume(device.getVolume() - 10);
    }

    @Override
    public void volumeUp() {
        System.out.println("Control remoto: botón de aumento del volumen");
        device.setVolume(device.getVolume() + 10);
    }

    @Override
    public void channelDown() {
        System.out.println("Control remoto: botón de disminución del canal");
        device.setChannel(device.getChannel() - 1);
    }

    @Override
    public void channelUp() {
        System.out.println("Control remoto: botón de aumento del canal");
        device.setChannel(device.getChannel() + 1);
    }
}