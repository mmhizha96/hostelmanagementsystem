import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HostelpreferenceComponent } from './hostelpreference.component';

describe('HostelpreferenceComponent', () => {
  let component: HostelpreferenceComponent;
  let fixture: ComponentFixture<HostelpreferenceComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HostelpreferenceComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(HostelpreferenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
